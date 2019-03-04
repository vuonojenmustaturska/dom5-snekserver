<?php

namespace Snek;

use Illuminate\Database\Eloquent\Model;
use Log;
use Cache;

class Map extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'maps';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'filename', 'provinces', 'imagefile', 'user_id'];

    public function user()
    {
        return $this->belongsTo('Snek\User');
    }

    public function games()
    {
        return $this->hasMany('Snek\Game');
    }

    public function getProvinceCounts()
    {
        return Cache::remember('map-provs-'.$this->id, 1440, function()
        {
            $maptext = file_get_contents('/home/dom5snek/dominions5/maps/'.$this->filename);

            //Log::info($this->filename);

            $counts = [
                'land' => 0,
                'underwater' => 0,
                'plains' => 0,
                'smallprov' => 0,
                'largeprov' => 0,
                'sea' => 0,
                'freshwater' => 0,
                'mountain' => 0,
                'swamp' => 0,
                'waste' => 0,
                'forest' => 0,
                'farm' => 0,
                'manysites' => 0,
                'deepsea' => 0,
                'cave' => 0,
                'bordermountain' => 0,
                ];

            preg_match_all('/#terrain\s[0-9]*\s([0-9]*)/', $maptext, $matches);

            //Log::info(print_r($matches, true));

            foreach ($matches[1] as $match)
            {
                if ($match & 1)
                    $counts['smallprov']++;

                if ($match & 2)
                    $counts['largeprov']++;


                if ($match & 2048)
                    $counts['deepsea']++;

                if ($match & 4)
                    $counts['sea']++;

                if ($match & 8)
                    $counts['freshwater']++;

                if ($match & 16)
                    $counts['mountain']++;
                
                if ($match & 32)
                    $counts['swamp']++;

                if ($match & 64)
                    $counts['waste']++;

                if ($match & 128)
                    $counts['forest']++;

                if ($match & 256)
                    $counts['farm']++;

                if ($match & 1024)
                    $counts['manysites']++;



                if ($match & 4096)
                    $counts['cave']++;

                if ($match & 4194304)
                    $counts['bordermountain']++;

                if (!($match & 4) && !($match & 8) && !($match & 16) && !($match & 32) && !($match & 64) && !($match & 128) && !($match & 256) && !($match & 2048) && !($match & 4096) && !($match & 4194304))
                    $counts['plains']++;

            }

           
            $counts['underwater'] = $counts['sea']+$counts['deepsea'];
            $counts['land'] = count($matches[1])-$counts['underwater'];

            return $counts;
        });
    }
}
