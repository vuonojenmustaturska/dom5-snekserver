<?php

namespace Snek\Console\Commands;

use Illuminate\Console\Command;

use Snek\Map;

use Log;

use Snek\Jobs\CreateThumbnail;

use Illuminate\Foundation\Bus\DispatchesJobs;

class CheckMapThumbnails extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maps:checkthumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks that map thumbnails exist and recreates them if missing.';

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
        foreach(Map::all() as $map)
        {
            if (empty($map->imagefile))
              continue;
            if (preg_match('/\r/', $map->imagefile))
            {
              $map->imagefile = preg_replace('/\r/', '', $map->imagefile);
              $this->info($map->name . ' had a carriage return in filename');
              $map->save();
            }

            if (file_exists('/home/dom5snek/dominions5/maps/'.$map->imagefile))
            {
                if (!file_exists('/home/dom5snek/snek/public/img/maps/'.$map->id.'.png') or !file_exists('/home/dom5snek/snek/public/img/maps/'.$map->id.'-lg.png'))
                {
                    $this->info($map->name . "({$map->id}) is missing a thumbnail, generating...");
                    $this->dispatch(new CreateThumbnail($map->id, 'map'));
                }
            }
        }
    }
}
