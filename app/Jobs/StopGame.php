<?php

namespace Snek\Jobs;

use Snek\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

use Snek\Game;

use Snek\Jobs\CleanupGame;


class StopGame extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, DispatchesJobs;

    protected $game;

    protected $cleanup = false;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Game $game, $cleanup = false)
    {
        $this->game = $game;

        if ($cleanup === true)
        {
            $this->cleanup = true;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        exec('systemctl --user stop snek-'.$this->game->id.'.service');

        if ($this->cleanup === true)
        {
            Game::destroy($this->game->id);

            $this->dispatch(new CleanupGame($this->game));
        }
    }
}
