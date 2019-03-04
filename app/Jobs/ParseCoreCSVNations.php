<?php

namespace Snek\Jobs;

use Snek\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Snek\Nation;
use Log;

class ParseCoreCSVNations extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nations = [];


        if (($handle = fopen("/home/dom5snek/snek/storage/app/nations.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                if (is_numeric($data[0]))
                {
                    $nations[$data[0]] = ['name' => $data[1],
                        'epithet' => $data[2],
                        'era' => $data[5]];
                }

            }
            fclose($handle);
        }

        foreach ($nations as $nationid => $nationdata)
        {

            $nation = Nation::firstOrNew(['nation_id' => $nationid, 'implemented_by' => null]);
            //Log::info("Inserting/updating nation {$nationid}: {$nationdata['name']}");
            $nation->update($nationdata);
        }
    }
}
