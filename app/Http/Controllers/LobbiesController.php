<?php

namespace Snek\Http\Controllers;

use Snek\Http\Requests;
use Snek\Http\Controllers\Controller;

use Snek\Lobby;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Snek\ServerState;
use Snek\Nation;
use Snek\Signup;
use DB;
use Snek\Game;
use Log;

class LobbyUser 
{
    public $name = '';

    public function __construct($name)
    {
        $this->name = '* '.$name;
    }
}

class LobbiesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        if (Auth::check() && Auth::user()->is_admin)
        {
            $lobbies = Lobby::paginate(15);

            return view('lobbies.index', compact('lobbies'));
        }
        else
        {
            return view('lobbies.mes');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('lobbies.create');
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function createfromgame($game_id)
    {
        $game = Game::findOrFail($game_id);

        return view('lobbies.create', compact('game'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Request $request)
    {
        
        Lobby::create($request->all());

        Session::flash('flash_message', 'Lobby added!');

        return redirect('lobbies');
    }

    public function json_nationmods()
    {
        $mods = DB::table('mods')
            ->select('mods.id', 'mods.name')
            ->join('nations', 'mods.id', '=', 'nations.implemented_by')
            ->distinct()
            ->get();

        //$mods = array_merge(['id' => null, 'name' => 'Dominions 4 core nations'], $mods);


        return response()->json($mods);
    }

    public function json_nations($id)
    {
        if ($id == 0)
            return response()->json( DB::table('nations')->select('id','nation_id','name','description','epithet','flag')->where('implemented_by','=',null)->get() );
        elseif (is_numeric($id))
            return response()->json( DB::table('nations')->select('id','nation_id','name','description','epithet','flag')->where('implemented_by','=',$id)->where('era', '!=', 0)->get() );
        else
            return (new Response('', 404));
    }

    public function json_corenations($id)
    {
        $lobby = Lobby::findOrFail($id);

        return response()->json( DB::table('nations')->select('id','nation_id','name','description','epithet','flag')->where('implemented_by',null)->where('era', $lobby->game->era)->get() );

    }

    public function json_lobbynations($id)
    {
        $lobby = Lobby::findOrFail($id);

        $nations = [];

        $game = $lobby->game;


        if (!count($game->mods()->where('disablesoldnations', true)->get()))
            $oldnationsdisabled = true;
        else
            $oldnationsdisabled = false;

        foreach (Nation::where('implemented_by', null)->get() as $nation) 
            $nations[$nation->nation_id] = $nation->toArray();



        foreach($lobby->game->orderedMods() as $mod)
        {
                foreach (Nation::where('implemented_by', $mod->id)->get() as $modnation)
                {
                    if (array_key_exists($modnation->nation_id, $nations))
                        array_merge($nations[$modnation->nation_id], array_filter($modnation->toArray()));
                    else
                        $nations[$modnation->nation_id] = $modnation->toArray();
                }
        }

        foreach ($nations as $nation)
        {
            if ($nation['nation_id'] < 100 && $oldnationsdisabled)
                unset($nations[$nation['nation_id']]);

            if ($nation['era'] != $game->era)
                unset($nations[$nation['nation_id']]);
        }

        //Log::info(print_r($nations,true));

        return response()->json($nations);

        /*if ($id == 0)
            return response()->json( DB::table('nations')->select('id','nation_id','name','description','epithet','flag')->where('implemented_by','=',null)->get() );
        elseif (is_numeric($id))
            return response()->json( DB::table('nations')->select('id','nation_id','name','description','epithet','flag')->where('implemented_by','=',$id)->where('era', '!=', 0)->get() );
        else
            return (new Response('', 404));*/
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
        $lobby = Lobby::findOrFail($id);

        $signupsById = [];

        foreach ($lobby->signups as $signup)
        {
            if (isset($signup->user_id) && $signup->user_id > 0)
            {
                $signupsById[$signup->nation_id] = $signup;
            }
            else
            {
                $pseudosignup = (object)$signup->toArray();
                $pseudosignup->user = new LobbyUser($signup->write_in);
                $signupsById[$signup->nation_id] = $pseudosignup;
            }
            $passwordsById[$signup->nation_id] = $signup->password;
        }

        
        /*
        try 
        {
            $state = new ServerState($lobby->server_address, $lobby->server_port);
            $state->fetch();
            $state->getMods();


            $mods = [];

            foreach ($state->mods as $mod)
                $mods[] = $mod['Name'];

            
        }
        catch (ErrorException $e)
        {
            $mods = [];
        }
        */
        $mods = [];
        $nations = Nation::getNationsByModsNames($mods);
        
        $mapprovs = $lobby->game->map->getProvinceCounts();

        return view('lobbies.show', compact('lobby', 'nations', 'signupsById', 'passwordsById', 'mapprovs'));
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
        $lobby = Lobby::findOrFail($id);

        return view('lobbies.edit', compact('lobby'));
    }

    public function signup($id, Request $request)
    {
        $lobby = Lobby::findOrFail($id);

        if (Auth::check())
        {
            $this->validate($request,
            [
                'password' => 'required|min:1'
            ]);

            if (Signup::where('lobby_id', $lobby->id)->
                        where('nation_id', $request->input('nation'))->
                        where('user_id', '!=', Auth::id())->count() > 0)
                return redirect()->action('LobbiesController@show', [$lobby->id]);

            $signup = Signup::updateOrCreate(['user_id' => Auth::id(), 'lobby_id' => $lobby->id], [
                'user_id' => Auth::id(),
                'lobby_id' => $lobby->id,
                'nation_id' => $request->input('nation'),
                'password' => $request->input('password')
                ]);
        }


        return redirect()->action('LobbiesController@show', [$lobby->id]);
    }


    public function remove_signup($lobbyid, $signupid, Request $request)
    {
        $lobby = Lobby::findOrFail($lobbyid);
        $signup = Signup::findOrFail($signupid);

        if (Auth::check() && (Auth::id() == $lobby->owner->id or Auth::id() == $signup->user->id))
        {
            $signup->delete();
        }


        return redirect()->action('LobbiesController@show', [$lobby->id]);
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
        
        $lobby = Lobby::findOrFail($id);
        $lobby->update($request->all());

        Session::flash('flash_message', 'Lobby updated!');

        return redirect('lobbies');
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
        Lobby::destroy($id);

        Session::flash('flash_message', 'Lobby deleted!');

        return redirect('lobbies');
    }
}
