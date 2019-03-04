<?php

namespace Snek\Http\Controllers;

use Snek\Http\Requests;
use Snek\Http\Controllers\Controller;

use Snek\Mod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Session;
use Log;
use Auth;
use ZipArchive;
use Snek\Jobs\CreateThumbnail;
use Snek\Jobs\ParseModNations;
use Snek\Jobs\ParseCoreCSVNations;
use Storage;
use Imagick;
use ImagickPixel;

class ModsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $mods = Mod::paginate(15);

        return view('mods.index', compact('mods'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return redirect('mods');

        return view('mods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Request $request)
    {
        return redirect('mods');

        Mod::create($request->all());

        Session::flash('flash_message', 'Mod added!');

        return redirect('mods');
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
        $mod = Mod::findOrFail($id);

        return view('mods.show', compact('mod'));
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
        return redirect('mods');

        $mod = Mod::findOrFail($id);

        return view('mods.edit', compact('mod'));
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
        return redirect('mods');

        $mod = Mod::findOrFail($id);
        $mod->update($request->all());

        Session::flash('flash_message', 'Mod updated!');

        return redirect('mods');
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
            Mod::destroy($id);

            Session::flash('flash_message', 'Mod deleted!');
  
        }
        else
        {
            Session::flash('flash_message', 'No.');
        }

        return redirect('mods');
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
            return view('mods.upload');
        }
        else
        {
            return redirect('mods');
        }
    }

    public function parsenations($id)
    {
        $this->dispatch(new ParseModNations($id));
        return redirect('mods');
    }

    public function parsecorenations()
    {
        $this->dispatch(new ParseCoreCSVNations());
        return redirect('mods');
    }

    public function getModAsset($asset)
    {
        $asset = preg_replace('/[\.]+?/', '.', $asset);

       // Log::info($asset);

        if (Storage::exists('mods/'.$asset))
        {
            return Response::make(Storage::get('mods/'.$asset), 200, ['Content-Type' => 'image/png']);
        }
        else
        {
            $rawasset = str_replace('.png', '', $asset);
            //Log::info($rawasset);
            if (!empty($asset) && file_exists('/home/dom5snek/dominions5/mods/'.$rawasset))
            {
                //Log::info($rawasset);
                $image = new Imagick();        
                $image->readImage('/home/dom5snek/dominions5/mods/'.$rawasset);
                $image->setImageFormat('png');
                $transparent = new ImagickPixel('#00000000');
                $image->opaquePaintImage($image->getImagePixelColor(0, 0), $transparent, 0, 0, Imagick::CHANNEL_ALL);
                //Log::info($asset);
                Storage::put('mods/'.$asset, $image->getImageBlob());
            }
        }

        if (Storage::exists('mods/'.$asset))
        {
            return Response::make(Storage::get('mods/'.$asset), 200, ['Content-Type' => 'image/png']);
        }
        else
        {   
            abort(404);
            //return (new Response('', 404));
        }
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
            'file' => 'max:16000',
        );
 
        $validation = Validator::make($input, $rules);
 
        if ($validation->fails()) {
            return Response::make($validation->errors()->first(), 400);
        }


        if (Input::file('file')->getClientOriginalExtension() == 'zip')
        {
            $mods = [];

            $zip = new ZipArchive();

            $zip->open(Input::file('file')->getRealPath());

            $files = [];

            for ($i=0; $i < $zip->numFiles; $i++) {

                if (!file_exists('/home/dom5snek/dominions5/mods/'.$zip->statIndex($i)['name']))
                    $files[] = $zip->statIndex($i)['name'];

                if (strpos($zip->statIndex($i)['name'], '/') === FALSE && stripos(strrev($zip->statIndex($i)['name']), 'md.') === 0) // If the file is a .dm mod description in the top level of the zip file
                {
                    if (Mod::where('filename', '=', $zip->statIndex($i)['name'])->exists())
                    {
                        // mod already exists in the database
                        continue;
                    }

                    $fp = $zip->getStream($zip->statIndex($i)['name']);

                    if(!$fp) 
                        return Response::make('Could not open '.$zip->statIndex($i)['name'], 400);

                    $modtext = '';

                    while (!feof($fp)) {
                        $modtext .= fread($fp, 1024);
                    }

                    fclose($fp);

                    $moddata = [];

                    preg_match('/#modname\\s"(.*)"/', $modtext, $matches);

                    Log::info('Uploaded mod title: '.print_r($matches,true));

                    if (isset($matches[1]) && strlen($matches[1]) > 1)
                    {
                        $moddata['name'] = $matches[1];
                    }
                    else
                    {
                        $moddata['name'] = str_replace('.dm', '', $zip->statIndex($i)['name']);
                    }

                    preg_match('/#description "(:?.+?)"/s', $modtext, $matches);
            
                    if (isset($matches[1]) && strlen($matches[1]) > 1)
                    {
                        $moddata['description'] = $matches[1];
                    }
                    else
                    {
                        $moddata['description'] = "";
                    }

                    preg_match('/#version\\s(.*)/', $modtext, $matches);

                    if (isset($matches[1]) && strlen($matches[1]) > 1)
                    {
                        $moddata['version'] = $matches[1];
                    }
                    else
                    {
                        $moddata['version'] = 1;
                    }


                    if (preg_match('/#disableoldnations/', $modtext))
                    {
                        $moddata['disablesoldnations'] = true;
                    }
                    else
                    {
                        $moddata['disablesoldnations'] = false;
                    }

                    preg_match('/#icon\\s(.*)/', $modtext, $matches);

                    if (isset($matches[1]) && strlen($matches[1]) > 1)
                    {

                        $moddata['icon'] = trim($matches[1], " \t\n\r\0\x0B\"");
                        if (substr($moddata['icon'], 0, strlen('./')) == './') {
                            $moddata['icon'] = substr($moddata['icon'], 2);
                        } 
                    }
                    else
                    {
                        $moddata['icon'] = '';
                    }

                    $moddata['user_id'] = Auth::id();

                    $modrules = [
                        'name' => 'required|min:4',
                        'description' => 'string',
                    ];

                    $modvalidator = Validator::make($moddata, $modrules);

                    if ($modvalidator->fails()) {
                        return Response::make($modvalidator->errors()->first(), 400);
                    }

                    $mod = Mod::firstOrNew(['filename' => $zip->statIndex($i)['name']]);

                    foreach ($moddata as $key => $value)
                    {
                        $mod->$key = $value;

                    }

                    $mods[] = $mod;

                }


            }

            if (count($mods) > 0 && count($files) > 0)
            {

                $zip->extractTo('/home/dom5snek/dominions5/mods/', $files);

                foreach ($mods as $mod)
                {
                    $mod->save();
                    $this->dispatch(new CreateThumbnail($mod->id, 'mod'));
                }

            }
            else
            {
                return Response::make('Could not find any new mods in the archive', 400);
            }


        } 
        else 
        {
            return Response::make('Uploaded file must be a zip archive', 400);
        }


        }

    }
}
