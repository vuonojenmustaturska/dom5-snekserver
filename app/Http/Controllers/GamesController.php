<?php

namespace Snek\Http\Controllers;

use Snek\Http\Requests;
use Snek\Http\Controllers\Controller;

use Snek\Game;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Log;
use Auth;
use Snek\ServerState;
use Snek\GamePermission;
use Storage;
use Snek\Nation;
use Snek\GameStat;

use Snek\Jobs\CreateNewGame;
use Snek\Jobs\StartGame;
use Snek\Jobs\RestartGame;
use Snek\Jobs\StopGame;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        $games = Game::paginate(15);

        return view('games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('games.create');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function server_status($id)
    {
        $game = Game::findOrFail($id);
        $state = new ServerState('snek.earth', 30000+$game->id);
        $state->fetch();
        $state->getMods();

        $mods = [];

        foreach ($state->mods as $mod)
            $mods[] = $mod['Name'];

        $nations = Nation::getNationsByModsNames($mods);


        /*if (($handle = fopen("/home/snek/snek/storage/app/nations.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                    $nations[$data[0]] = ['name' => $data[1],
                    'epithet' => $data[2],
                    'abbreviation' => $data[3],
                    'file_name_base' => $data[4],
                    'era' => $data[5]];
            }
            fclose($handle);
        } */


        return view('games.status', compact('game','state', 'nations'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function store(Request $request)
    {
        if (Auth::check() && Auth::user()->is_vouched)
        {
            $request['user_id'] = Auth::id();
            $request['shortname'] = preg_replace("/[^A-Za-z0-9]/", '', $request['name']);

            $this->validate($request,
            [
                'name' => 'required|min:1',
                'shortname' => 'required|min:1',
                'era' => 'required|min:1|max:3',
                'port' => 'numeric|min:50000|max:60000',
                'magicsites' => 'numeric|min:0|max:75',
                'eventrarity' => 'numeric|min:1|max:2',
                'research' => 'numeric|min:0|max:4',
                'storyevents' => 'numeric|min:0|max:2',
                'globals' => 'numeric|min:3|max:9',
                'richness' => 'numeric|min:50|max:300',
                'supplies' => 'numeric|min:50|max:300',
                'resources' => 'numeric|min:50|max:300',
                'masterpw' => 'min:4|max:255',
                'victorycond' => 'required|numeric|min:0|max:2',
                'teamgame' => 'required|numeric|min:0|max:1',
                'lvl1thrones' => 'required_unless:victorycond,0|numeric|min:0|max:20',
                'lvl2thrones' => 'required_unless:victorycond,0|numeric|min:0|max:15',
                'lvl3thrones' => 'required_unless:victorycond,0|numeric|min:0|max:10',
                'totalvp' => 'numeric|min:0|max:25',
                'mods' => ['array','exists:mods,id'],
                'map_id' => 'required|numeric|exists:maps,id',
            ]);


            $game = Game::create($request->all());

            //Log::info(print_r($request->input('mods'),true));

            if ($request->input('mods'))
                {
                    $sync_data = [];

                    for($i = 0; $i < count($request->input('mods')); $i++)
                        $sync_data[$request->input('mods')[$i]] = ['load_order' => $request->input('load-order-'.$request->input('mods')[$i])];

                    //Log::info(print_r($sync_data, true));
                    /*foreach ($request->input('mods') as $mod)
                    {
                        Log::info($mod.': '.$request->input('load-order-'.$mod));
                    }*/
                    
                    $game->mods()->sync($sync_data);
            }

            //$game->mods()->attach($request->input('mods'));

            Session::flash('flash_message', 'Game added!');

            return redirect('games');
        }
        else
        {
            return redirect('games');
        }
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
        $game = Game::findOrFail($id);

        if ($game->id == 129)
        {
            $foo = '';
            foreach($game->mods as $mod)
            {
                $foo .= $mod->id.': '.$mod->pivot->load_order.', ';
            }
            Log::info($foo);
        }

        return view('games.show', compact('game'));
    }

    public function stats($id)
    {
        $game = Game::findOrFail($id);

        $stats = GameStat::where('game_id', '=', $game->id)->orderBy('turn')->get();

        $chartdata = [];
        $trackedstats = ['dominion', 'armysize', 'research', 'income', 'gemincome', 'victorypoints', 'provinces', 'forts'];

        $labels = [];
        $data = [];
        $nationturns = [];
        $turn = -1;

        $turns = [];

        foreach ($trackedstats as $sstat)
            $chartdata[$sstat] = [];

        foreach ($stats as $stat)
        {

            if ($turn != $stat->turn)
            {
                if (!empty($data))
                {
                    foreach ($trackedstats as $sstat)
                    {
                        $data[$sstat]['turn'] = $turn;
                        $chartdata[$sstat][] = $data[$sstat];
                    }
                }

               // Log::info('turn changed from '.$turn.' to '.$stat->turn);
                
                $turn = $stat->turn;

                foreach ($trackedstats as $sstat) 
                    $data[$sstat] = [];
            }

            

            if (!array_key_exists($stat->nation_id, $labels))
                $labels[$stat->nation_id] = Nation::findOrFail($stat->nation_id)->name;

            $turns[$turn][$labels[$stat->nation_id]] = $stat->turn_status;
            $nationturns[$labels[$stat->nation_id]][$turn] = $stat->turn_status;

            foreach ($trackedstats as $sstat)
                $data[$sstat][$stat->nation_id] = $stat->$sstat;


        }

        Log::info(print_r($chartdata,true));

        foreach ($labels as $number => $label)
        {
            $chartdata['ykeys'][] = $number;
            $chartdata['labels'][] = $label;
        }

        foreach ($trackedstats as $sstat)
                $chartdata[$sstat] = json_encode($chartdata[$sstat]);
        
        $chartdata['ykeys'] = json_encode($chartdata['ykeys']);
        $chartdata['labels'] = json_encode($chartdata['labels']);

        return view('games.stats', compact('game', 'chartdata', 'trackedstats', 'turns', 'nationturns'));
    }

    /**
     * Sets up a server instance
     *
     * @param  int  $id
     *
     * @return void
     */
    public function server_createnew($id)
    {
        if (Auth::check())
        {
            $game = Game::findOrFail($id);
            if (Auth::id() == $game->user_id || Auth::user()->is_admin)
            {
                $game->state = 1;
                $game->save();


                $this->dispatch(new CreateNewGame($game));


                return redirect()->action('GamesController@show', [$game->id]);
            }


        }
        else
        {
            return redirect('games');
        }

    }

    /**
     * Starts a game server
     *
     * @param  int  $id
     *
     * @return void
     */
    public function server_start($id)
    {
        if (Auth::check())
        {
            $game = Game::findOrFail($id);

            if (Auth::id() == $game->user_id || Auth::user()->is_admin)
            {
                $game->state = 3;
                $game->save();

                $this->dispatch(new StartGame($game));

                return redirect()->action('GamesController@show', [$game->id]);
            }
            
        }
        else
        {
            return redirect('games');
        }
    }

    /**
     * Restarts a game server
     *
     * @param  int  $id
     *
     * @return void
     */
    public function server_restart($id)
    {

        if (Auth::check())
        {
            $game = Game::findOrFail($id);
            Log::info('GamesController: asked to restart game: '.$id.'. State is: '.$game->state);
            
            if (GamePermission::canRestart($game, Auth::user()))
            {
                

                $this->dispatch(new RestartGame($game));

                return redirect()->action('GamesController@show', [$game->id]);
            }
            
        }
        else
        {
            return redirect('games');
        }
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
        $game = Game::findOrFail($id);

        if (Auth::id() == $game->user_id)
        {
            $mapname = $game->map->name;
            $asdf = 1;
            return view('games.edit', compact('game', 'mapname', 'asdf'));
        }
        else
        {
            return redirect('games');
        }

        
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
        $this->validate($request,
        [
            'era' => 'sometimes|required|min:1|max:3',
            'magicsites' => 'sometimes|numeric|min:0|max:75',
            'eventrarity' => 'sometimes|numeric|min:1|max:2',
            'research' => 'sometimes|numeric|min:0|max:4',
            'richness' => 'sometimes|numeric|min:50|max:300',
            'supplies' => 'sometimes|numeric|min:50|max:300',
            'resources' => 'sometimes|numeric|min:50|max:300',
            'masterpw' => 'sometimes|min:4|max:255',
            'victorycond' => 'sometimes|numeric|min:0|max:2',
            'storyevents' => 'sometimes|numeric|min:0|max:2',
            'teamgame' => 'sometimes|numeric|min:0|max:1',
            'lvl1thrones' => 'sometimes|required_unless:victorycond,0|numeric|min:0|max:20',
            'lvl2thrones' => 'sometimes|required_unless:victorycond,0|numeric|min:0|max:15',
            'lvl3thrones' => 'sometimes|required_unless:victorycond,0|numeric|min:0|max:10',
            'hours' => 'sometimes|numeric',
            'masterpw' => 'sometimes|min:4|max:255',
            'map_id' => 'sometimes|numeric|exists:maps,id',
            'mods' => ['array','exists:mods,id'],
        ]);

        $game = Game::findOrFail($id);

        if (Auth::id() == $game->user_id)
        {
        	if ($game->state >= 3)
        	{
        		$game->update($request->only('masterpw', 'hours'));
        	}
            elseif ($game->state > 0 && $game->state < 3)
            {
                $game->update($request->only('masterpw', 'hours', 'map_id'));
            }
            else
            {
                $game->update($request->except('id', 'name', 'shortname'));
                if ($request->input('mods'))
                {
                    $game->mods()->detach();

                    $sync_data = [];

                    for($i = 0; $i < count($request->input('mods')); $i++)
                        $sync_data[$request->input('mods')[$i]] = ['load_order' => $request->input('load-order-'.$request->input('mods')[$i])];

                    Log::info(print_r($sync_data, true));
                    /*foreach ($request->input('mods') as $mod)
                    {
                        Log::info($mod.': '.$request->input('load-order-'.$mod));
                    }*/
                    
                    $game->mods()->sync($sync_data);
                }
            }
            




            Session::flash('flash_message', 'Game updated!');

        }

        return redirect()->route('games.show', ['games' => $game->id]);
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
        $game = Game::findOrFail($id);
        
        if (Auth::id() == $game->user_id)
        {
            $this->dispatch(new StopGame($game, true));

            Session::flash('flash_message', 'Game queued for deletion!');
        }
        else
        {
            Session::flash('flash_message', 'Please don\'t try to delete someone else\'s game');
        }

        return redirect('games');
    }
}
