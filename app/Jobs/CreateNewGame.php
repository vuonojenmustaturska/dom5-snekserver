<?php

namespace Snek\Jobs;

use Snek\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use Illuminate\Contracts\Queue\ShouldQueue;

use Snek\Game;

class CreateNewGame extends Job implements ShouldQueue
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
        file_put_contents('/home/dom5snek/.config/systemd/user/snek-'.$this->game->id.'.service', $this->game->getUnitFile());
        exec('systemctl --user daemon-reload');
        $out = [];
        $ret = null;
        exec('systemctl --user start snek-'.$this->game->id.'.service 2>&1', $out, $ret);
        if ($ret === 0)
        {
            Log::info('attempting to set state: 2');
            $this->game->state = 2;
            $this->game->save();
            Log::info('set state: 2');
        }
        Log::info('systemctl result: '.$ret);
        Log::info(getenv('DBUS_SESSION_BUS_ADDRESS'));
        Log::info(print_r($out,true));
    }
}
