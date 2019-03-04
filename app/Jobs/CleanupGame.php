<?php

namespace Snek\Jobs;

use Snek\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CleanupGame extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $game;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($game)
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
        if (is_file('/home/dom5snek/.config/systemd/user/snek-'.$this->game->id.'.service'))
            unlink('/home/dom5snek/.config/systemd/user/snek-'.$this->game->id.'.service');
        exec('systemctl --user daemon-reload');
    }
}
