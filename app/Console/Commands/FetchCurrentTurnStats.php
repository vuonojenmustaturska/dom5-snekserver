<?php

namespace Snek\Console\Commands;

use Illuminate\Console\Command;
use Snek\Game;
use Snek\Nation;
use Snek\GameStat;
use Snek\ServerState;

class FetchCurrentTurnStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:currentturn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses current turn game stats';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        foreach (Game::where('state', '>', 0)->where('state', '<', 6)->where('status', 1)->get() as $game)
        {
        	try 
	        {
	        	$state = new ServerState('snek.earth', 30000+$game->id);
	        	$state->fetch();

	        	$this->info('Name: '.$state->name);
	        	$this->info('Turn: '.$state->turn.', time to host: '.$state->tth);
	        	
	        	if ($state->turn == 4294967295)
	        		continue;

	        	foreach ($state->nations as $id => $nation)
	        	{
	        		if ($nation['status'] == 2)
	        		{
	        			$status = 'AI';
	        		}
	        		elseif ($nation['status'] == 254 or $nation['status'] == 255)
	        		{
	        			$status = 'Defeated';
	        		}
	        		elseif ($nation['status'] == 1)
	        		{
	        			switch ($nation['submitted'])
	        			{
	        				case 0:
	        					$status = '-';
	        					break;
	        				case 1:
	        					$status = 'Partial';
	        					break;
	        				case 2:
	        					$status = 'Turn played';
	        					break;
	        			}
	        		}
	        		else
	        		{
	        			continue;
	        		}
	        		GameStat::updateOrCreate(['turn' => $state->turn,'nation_id' => $id, 'game_id' => $game->id ], ['turn_status' => $status]);
	        	}
	        	//$this->info('Nations:'.print_r($state->nations, true));
	        }
	        catch (Exception $e)
	        {
	        	$this->error('Error parsing '.$game->name.': '.$e->getMessage());
	        }
        }


    
    }

}
