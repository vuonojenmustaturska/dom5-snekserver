<?php

namespace Snek\Jobs;

use Snek\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Log;
use Snek\Mod;
use Snek\Nation;

class ParseModNations extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $mod;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->mod = Mod::findOrFail($id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Starting nations parse on mod {$this->mod->id}: {$this->mod->name}");

        if (!file_exists('/home/dom5snek/dominions5/mods/'.$this->mod->filename))
        {
            Log::error("Something has gone terribly wrong.");
            return;
        }

        $modtext = file_get_contents('/home/dom5snek/dominions5/mods/'.$this->mod->filename);

        Log::info('textlen:'.strlen($modtext));

        $nations = [];

        if (preg_match_all('/#selectnation\\s([0-9]+)(.+?)#end/s', $modtext, $matches, PREG_SET_ORDER) > 0)
        {
            foreach ($matches as $match)
            {
                $nation = [];

                preg_match('/#name\s+?"(.*)"/', $match[2], $nationmatch);

                if (isset($nationmatch[1]) && strlen($nationmatch[1]) > 0)
                {
                    $nation['name'] = $nationmatch[1];
                }

                preg_match('/#era\s+?(\d)/', $match[2], $nationmatch);

                if (isset($nationmatch[1]) && strlen($nationmatch[1]) > 0)
                {
                    $nation['era'] = $nationmatch[1];
                }


                preg_match('/#flag[\s]+?"[\.\/]*(.+?)"/', $match[2], $nationmatch);

                if (isset($nationmatch[1]) && strlen($nationmatch[1]) > 0)
                {
                    $nation['flag'] = trim($nationmatch[1], " \t\n\r\0\x0B\"");
                    $nation['flag'] = str_replace('//', '/', $nation['flag']);
                    Log::info('flag: '.$nation['flag']);
                }


                preg_match('/#epithet\s+?"(.+?)"/s', $match[2], $nationmatch);

                if (isset($nationmatch[1]) && strlen($nationmatch[1]) > 0)
                {
                    $nation['epithet'] = $nationmatch[1];
                }

                preg_match('/#descr\\s"(.+?)"/s', $match[2], $nationmatch);

                if (isset($nationmatch[1]) && strlen($nationmatch[1]) > 0)
                {
                    $nation['description'] = $nationmatch[1];
                }

                preg_match('/#brief\\s"(.+?)"/s', $match[2], $nationmatch);

                if (isset($nationmatch[1]) && strlen($nationmatch[1]) > 0)
                {
                    $nation['brief'] = $nationmatch[1];
                }


                $nation['implemented_by'] = $this->mod->id;
                $nations[$match[1]] = $nation;
            }
        }



        foreach ($nations as $nationid => $nationdata)
        {
            foreach ($nationdata as &$text)
                $text = utf8_encode($text);
            
            $nation = Nation::firstOrNew(['nation_id' => $nationid, 'implemented_by' => $nationdata['implemented_by']]);
            $nation->update($nationdata);
            $nation->save();
        }
                    
    }
}
