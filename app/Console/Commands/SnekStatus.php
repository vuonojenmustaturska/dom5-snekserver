<?php

namespace Snek\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

use Snek\Game;

class SnekStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snekstatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates game status';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->comment(PHP_EOL.Inspiring::quote().PHP_EOL);
        foreach (Game::where('state', '>', 0)->where('state', '<', 6)->get() as $game)
        {
            $status = shell_exec('systemctl --user status snek-'.$game->id);
            $journal = shell_exec('journalctl -o cat -n --user-unit snek-'.$game->id);
            if(preg_match('/Active: active \(running\)/', $status))
            {
                $game->status = 1;
            }
            elseif (preg_match('/Active: inactive \(dead\)/', $status))
            {
                $game->status = 2;
            }
            else
            {
                $game->status = 0;
            }

            $game->journal = trim($journal);

            $game->save();
        }
    }


}
