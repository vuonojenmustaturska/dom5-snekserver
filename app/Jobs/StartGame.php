<?php

namespace Snek\Jobs;

use Snek\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Snek\Game;

class StartGame extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $game;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->game->state >= 3)
        {
            exec('systemctl --user stop snek-'.$this->game->id.'.service');
            file_put_contents('/home/dom5snek/.config/systemd/user/snek-'.$this->game->id.'.service', $this->game->getUnitFile());
            exec('systemctl --user daemon-reload');
            system('systemctl --user start snek-'.$this->game->id.'.service');
            $this->game->state = 4;
            $this->game->save();
        }

    }
}
