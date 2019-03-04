<?php

namespace Snek\Console\Commands;

use Illuminate\Console\Command;
use Snek\Game;
use Snek\Nation;
use Snek\GameStat;

class ParseGameStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:parsestats {gameId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses per-turn game stats';

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
        $game = Game::findOrFail($this->argument('gameId'));

        if (!file_exists('/home/dom5snek/dominions5/savedgames/'.$game->shortname))
            $this->error('Game save directory could not be found');
        else
        {
            $stats = [];
            $nations = [];
            if (file_exists('/home/dom5snek/dominions5/savedgames/'.$game->shortname.'/status.html'))
            {
                $statuses = file_get_contents('/home/dom5snek/dominions5/savedgames/'.$game->shortname.'/status.html');
                preg_match('/<td class="blackbolddata" colspan="2">.+?turn ([0-9]+?)\s{0,1}.+?td>/', $statuses, $turnmatch);
                $turn = $turnmatch[1];
                $this->info('statusturn: '.$turn);
                preg_match_all('/<\/tr>\n<tr>\n<td class="(?:.+?)">(?P<shortname>.+?), (?P<epithet>.+?)<\/td>\n<td .+?>(?P<status>.+?)<\/td>\n/s', $statuses, $statusmatches);
                foreach ($statusmatches['shortname'] as $key => $value)
                {
                    $nation = $game->nationByNameEpithet($value, $statusmatches['epithet'][$key]);

                    if ($nation['nation_id'] == 0)
                        continue;

                    $nations[$value]['nation_id'] = $nation['nation_id'];
                    $nations[$value]['turn_status'] = $statusmatches['status'][$key];
                    $nations[$value]['game_id'] = $game->id;
                    $nations[$value]['turn'] = $turn;
                }
                //$this->info(print_r($nations, true));
                /*foreach($statmatches['stat'] as $key => $statname)
                {
                   $this->info('Processing '.$statname); 
                   preg_match_all('/<tr>\n<td>(?P<nation>.+?)<\/td>\n<td>(?P<value>.+?)<\/td>\n<\/tr>/', $statmatches[2][$key], $statmatch);
                   foreach ($statmatch['nation'] as $nationkey => $nationname)
                   {
                    if ($nationname == 'Nation')
                        continue;
                    if ($nationname == 'Special Monsters')
                        $nationname = 'Special Monsters '.($nationkey-1);
                    
                    $stats[$nationname][$statname] = $statmatch['value'][$nationkey];
                   }
                }*/
            }
            if (file_exists('/home/dom5snek/dominions5/savedgames/'.$game->shortname.'/scores.html'))
            {
                $scores = file_get_contents('/home/dom5snek/dominions5/savedgames/'.$game->shortname.'/scores.html');
                preg_match('/<title>.+?turn ([0-9]+?)<\/title>/', $scores, $turnmatch);
                $turn = $turnmatch[1];
                $this->info('scoresturn: '.$turn);
                preg_match_all('/<h4>(?P<stat>[A-z\s]+?)<\/h4>\n<table .+?>\n((?:<tr>.+?<\/tr>\n)+?)<\/table>/s', $scores, $statmatches);
                foreach($statmatches['stat'] as $key => $statname)
                {
                   $this->info('Processing '.$statname); 
                   preg_match_all('/<tr>\n<td>(?P<nation>.+?)<\/td>\n<td>(?P<value>.+?)<\/td>\n<\/tr>/', $statmatches[2][$key], $statmatch);
                   foreach ($statmatch['nation'] as $nationkey => $nationname)
                   {
                    if ($nationname == 'Nation')
                        continue;
                    if ($nationname == 'Special Monsters')
                        continue;
                    if ($nationname == 'Independents')
                        continue;

                    $statname = strtolower(str_replace(' ', '', $statname));
                    $nations[$nationname][$statname] = $statmatch['value'][$nationkey];
                   }
                }
            }

        }
        //$this->info(print_r($nations, true));
        
        foreach ($nations as $key => $value)
        {
            GameStat::updateOrCreate(['turn' => $value['turn'],'nation_id' => $value['nation_id'], 'game_id' => $value['game_id'] ], $value);
        }
        

    
    }

}
