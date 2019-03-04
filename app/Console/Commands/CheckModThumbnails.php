<?php

namespace Snek\Console\Commands;

use Illuminate\Console\Command;

use Snek\Mod;

use Snek\Jobs\CreateThumbnail;

use Illuminate\Foundation\Bus\DispatchesJobs;

class CheckModThumbnails extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mods:checkthumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks that mod thumbnails exist and recreates them if missing.';

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
        foreach(Mod::all() as $mod)
        {
            if (!empty($mod->icon) && file_exists('/home/dom5snek/dominions5/mods/'.$mod->icon))
            {
                if (!file_exists('/home/dom5snek/snek/public/img/mods/'.$mod->id.'.png') or !file_exists('/home/dom5snek/snek/public/img/mods/'.$mod->id.'-xs.png'))
                {
                    $this->info($mod->name . ' is missing a thumbnail, generating...');
                    $this->dispatch(new CreateThumbnail($mod->id, 'mod'));
                }
            }
            else
            {
                if (!file_exists('/home/dom5snek/snek/public/img/mods/'.$mod->id.'.png') or !file_exists('/home/dom5snek/snek/public/img/mods/'.$mod->id.'-xs.png'))
                {
                    $this->info($mod->name . ' is missing a thumbnail, using placeholder kitty...');
                    copy('/home/dom5snek/snek/public/img/140x140.png', '/home/dom5snek/snek/public/img/mods/'.$mod->id.'.png');
                    copy('/home/dom5snek/snek/public/img/128x32.png', '/home/dom5snek/snek/public/img/mods/'.$mod->id.'-xs.png');
                }
            }
        }
    }
}
