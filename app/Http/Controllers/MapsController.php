<?php

namespace Snek\Http\Controllers;

use Snek\Http\Requests;
use Snek\Http\Controllers\Controller;

use Snek\Map;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Session;
use ZipArchive;
use Log;

use Auth;

use Snek\Jobs\CreateThumbnail;

class MapsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $maps = Map::paginate(15);

        return view('maps.index', compact('maps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return redirect('maps/upload');
        /*
        return view('maps.create');
        */
    }

    /**
     * Show the form for uploading a new map.
     *
     * @return void
     */
    public function upload()
    {
        if (Auth::check() && Auth::user()->is_vouched)
        {
            return view('maps.upload');
        }
        else
        {
            return redirect('maps');
        }
    }

    public function regeneratethumbnail($id)
    {
        $this->dispatch(new CreateThumbnail($id, 'map'));
        return redirect('maps');
    }
    /**
     * Handles a file upload
     *
     * @return void
     */


    public function handleupload()
    {
        if (Input::hasFile('file'))
        {


        $input = Input::all();



        $rules = array(
            'file' => 'max:64000',
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {
            return Response::make($validation->errors()->first(), 400);
        }


        if (Input::file('file')->getClientOriginalExtension() == 'map')
        {
            if (Map::where('filename', '=', str_replace('/', '_', Input::file('file')->getClientOriginalName()))->exists())
            {
                return Response::make('Map already exists.', 400);
            }

            $maptext = file_get_contents(Input::file('file')->getRealPath());

            preg_match('/#dom2title\\s(.*)\r?\n/', $maptext, $matches);

            Log::info('Uploaded map title: '.print_r($matches,true));

            if (isset($matches[1]) && strlen($matches[1]) > 1)
            {
                $mapname = $matches[1];
            }
            else
            {
                $mapname = str_replace('.'.Input::file('file')->getClientOriginalExtension(), '', Input::file('file')->getClientOriginalName());
            }

            preg_match('/#description\\s"((:?[\\s\\S])+?)"\r?\n/', $maptext, $matches);

            if (isset($matches[1]) && strlen($matches[1]) > 1)
            {
                $mapdesc = $matches[1];
            }
            else
            {
                $mapdesc = "";
            }

            preg_match('/#imagefile\\s(.*)\r?\n/', $maptext, $matches);

            if (isset($matches[1]) && strlen($matches[1]) > 1)
            {
                $mapimage = preg_replace('/\r/', '', $matches[1]);
            }
            else
            {
                $mapimage = "";
            }

            preg_match_all('/#terrain ([0-9]*)/', $maptext, $matches);

            $mapprovinces = count(array_unique($matches[1]));

            $maparray = [
                'name' => $mapname,
                'description' => $mapdesc,
                'provinces' => $mapprovinces,
                'filename' => str_replace('/', '_', Input::file('file')->getClientOriginalName()),
                'imagefile' => $mapimage,
                ];

            $maprules = [
                    'name' => 'required|min:4',
                    'description' => 'string',
                    'provinces' => 'numeric|min:10|max:2000',
                ];

            $mapvalidator = Validator::make($maparray, $maprules);

            if ($mapvalidator->fails()) {
                return Response::make($mapvalidator->errors()->first(), 400);
            }

            $maparray['user_id'] = Auth::id();

            $map = Map::create($maparray);

            $this->dispatch(new CreateThumbnail($map->id, 'map'));

            $message = $mapname . ' ('.$mapprovinces.' provinces) uploaded.';

        }
        else
        {
            if (Map::where('imagefile', '=', str_replace('/', '_', Input::file('file')->getClientOriginalName()))->exists() && file_exists('/home/dom5snek/dominions5/maps/'.Input::file('file')->getClientOriginalName()))
            {
                return Response::make('Map already exists.', 400);
            }
        	$message = str_replace('/', '_', Input::file('file')->getClientOriginalName()).' uploaded.';
        }

            $upload_success = Input::file('file')->move('/home/dom5snek/dominions5/maps/', str_replace('/', '_', Input::file('file')->getClientOriginalName()));

            if ($upload_success) {
                return Response::json(['success', 'message' => $message], 200);
            } else {
                return Response::make('file save failed', 400);
            }
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Request $request)
    {
        return redirect('maps');

        /*
        $this->validate($request, ['' => 'string', ]);

        Map::create($request->all());

        Session::flash('flash_message', 'Map added!');

        return redirect('maps');
        */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function show($id)
    {
        $map = Map::findOrFail($id);

        $mapprovs = $map->getProvinceCounts();

        return view('maps.show', compact('map', 'mapprovs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function edit($id)
    {
        return redirect('maps');
        /*
        $map = Map::findOrFail($id);

        return view('maps.edit', compact('map')); */
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function update($id, Request $request)
    {
        return redirect('maps');
       /* $this->validate($request, ['' => 'string', ]);

        $map = Map::findOrFail($id);
        $map->update($request->all());

        Session::flash('flash_message', 'Map updated!');

        return redirect('maps'); */
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function destroy($id)
    {
        if (Auth::id() == 1)
        {
            Map::destroy($id);

            Session::flash('flash_message', 'Map deleted!');
        }
        else
        {
            Session::flash('flash_message', 'No.');
        }

        return redirect('maps');
    }
}
