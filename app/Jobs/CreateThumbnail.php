<?php

namespace Snek\Jobs;

use Snek\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Snek\Map;
use Snek\Mod;

use Imagick;

class CreateThumbnail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $mode;
    protected $item_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $mode)
    {
        $this->item_id = $id;
        $this->mode = $mode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->mode == 'map')
        {
            $map = Map::findOrFail($this->item_id);

            if (!empty($map->imagefile) && file_exists('/home/dom5snek/dominions5/maps/'.$map->imagefile))
            {
           //     $image = new Imagick();        
           //     $image->readImage('/home/dom5snek/dominions5/maps/'.$map->imagefile);
           //     $image->thumbnailImage(140, 140,true,true);
           //     $image->writeImage ('/home/dom5snek/snek/public/img/maps/'.$map->id.'.png');
           //     $image->readImage('/home/dom5snek/dominions5/maps/'.$map->imagefile);
           //     $image->thumbnailImage(400, 400,true);
           //     $image->writeImage ('/home/dom5snek/snek/public/img/maps/'.$map->id.'-lg.png');
            }
        }
        elseif ($this->mode == 'mod')
        {
            $mod = Mod::findOrFail($this->item_id);

            if (!empty($mod->icon) && file_exists('/home/dom5snek/dominions5/mods/'.$mod->icon))
            {
            //    $image = new Imagick();        
             //   $image->readImage('/home/dom5snek/dominions5/mods/'.$mod->icon);
              //  $image->thumbnailImage(140, 140,true,true);
              //  $image->writeImage ('/home/dom5snek/snek/public/img/mods/'.$mod->id.'.png');
              //  $image->readImage('/home/dom5snek/dominions5/mods/'.$mod->icon);
              //  $image->thumbnailImage(128, 32,true,true);
              //  $image->writeImage ('/home/dom5snek/snek/public/img/mods/'.$mod->id.'-xs.png');
            }
        }
    }
}
