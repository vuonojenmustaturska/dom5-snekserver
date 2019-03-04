<?php

namespace Snek;

use Iatstuti\Database\Support\NullableFields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Cache;
use Log;

class Game extends Model
{
	use NullableFields;
    use SoftDeletes;
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'games';

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
    protected $fillable = ['name', 'port', 'masterpw', 'map_id', 'era', 'hours', 'hofsize', 'indepstr', 'magicsites', 'eventrarity', 'richness', 'resources', 'startprov', 'scoregraphs', 'nonationinfo', 'noartrest', 'teamgame', 'clustered', 'victorycond', 'requiredap', 'lvl1thrones', 'lvl2thrones', 'lvl3thrones', 'totalvp', 'capitalvp', 'requiredvp', 'summervp', 'user_id', 'renaming', 'shortname', 'research', 'globals', 'cataclysm', 'storyevents', 'norandres'];


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $nullable = [
        'hours',
        'hofsize',
        'port',
        'indepstr',
        'magicsites',
        'eventrarity',
        'richness',
        'resources',
        'startprov',
        'requiredap',
        'requiredvp',
        'lvl1thrones',
        'lvl2thrones',
        'lvl3thrones',
        'totalvp',
        'requirevp',
        'supplies',
        'cataclysm'
    ];

    /**
     * User who created this game
     */
    public function user()
    {
        return $this->belongsTo('Snek\User');
    }

    /**
     * The mods used in this game
     */
    public function mods()
    {
        return $this->belongsToMany('Snek\Mod')->withPivot('load_order');
    }



    public function orderedMods()
    {
        return $this->mods()->orderBy('game_mod.load_order', 'asc')->get();
    }

    public function stats()
    {
        return $this->hasMany('Snek\GameStat');
    }

    public function nationrules()
    {
        return $this->hasMany('Snek\NationRule');
    }

    public function permissions()
    {
        return $this->hasMany('Snek\GamePermission');
    }

    /**
     * Map
     */
    public function map()
    {
        return $this->belongsTo('Snek\Map');
    }

    public function getQuotedName()
    {
    	return "'".$this->name."'";
    }

    public function nationcachekey()
    {
        $key = [];

        foreach ($this->orderedMods() as $mod)
            $key[] = $mod->id;

        return 'nations-'.$this->era.'-'.implode('-', $key);
    }

    public function nations()
    {
        //Cache::forget($this->nationcachekey());
        return Cache::remember($this->nationcachekey(), 1440, function()
        {
            $nations = [];


            if (count($this->mods()->where('disablesoldnations', true)->get()))
                $oldnationsdisabled = true;
            else
                $oldnationsdisabled = false;

            foreach (Nation::where('implemented_by', null)->get() as $nation) 
                $nations[$nation->nation_id] = $nation->toArray();



            foreach($this->orderedMods() as $mod)
            {
                    foreach (Nation::where('implemented_by', $mod->id)->get() as $modnation)
                    {
                        if (array_key_exists($modnation->nation_id, $nations))
                            array_merge($nations[$modnation->nation_id], array_filter($modnation->toArray()));
                        else
                            $nations[$modnation->nation_id] = $modnation->toArray();
                    }
            }

            //Log::info($oldnationsdisabled);
            foreach ($nations as $nation)
            {
                if ($nation['nation_id'] < 5)
                    unset($nations[$nation['nation_id']]);

                if ($nation['nation_id'] < 109 && $oldnationsdisabled)
                    unset($nations[$nation['nation_id']]);

                if ($nation['era'] != $this->era)
                    unset($nations[$nation['nation_id']]);
            }

            //Log::info(print_r($nations,true));

            usort($nations, function($a, $b) {
                return $a['name'] <=> $b['name'];
            });

            return ($nations);
        });

    }

    public function nationById($id)
    {
        //Cache::forget($this->nationcachekey().'-n'.$id);
        return Cache::remember($this->nationcachekey().'-n'.$id, 1440, function() use (&$id)
        {
            //Log::info(print_r($this->nations(), true));
            //Log::info('Game nation:'.$id);
            foreach ($this->nations() as $nation)
            {
                if ($nation['nation_id'] == $id)
                    return $nation;
            }

            return ['nation_id' => 0, 'name' => 'error', 'epithet' => 'error', 'flag' => 'error', 'era' => '0'];
        });
    }


    public function nationByNameEpithet($name, $epithet)
    {
        //Cache::forget('nation-'.hash('sha256', $name.'-'.$epithet));
        return Cache::remember('nation-'.hash('sha256', $name.'-'.$epithet), 1440, function() use ($name, $epithet)
        {
            //Log::info(print_r($this->nations(), true));
            //Log::info('Game nation:'.$id);
            foreach ($this->nations() as $nation)
            {
                if ($nation['name'] == $name && $nation['epithet'] == $epithet)
                    return $nation;
            }

            return ['nation_id' => 0, 'name' => 'error', 'epithet' => 'error', 'flag' => 'error', 'era' => '0'];
        });
    }


    public function getNonDefaultSettings()
    {
        $defaultvalues = [
            'research' => 1,
            'hofsize' => 10,
            'indepstr' => 5,
            'magicsites' => 40,
            'eventrarity' => 1,
            'richness' => 100,
            'resources' => 100,
            'supplies' => 100,
            'cataclysm' => null,
            'startprov' => 1,
            'renaming' => false,
            'scoregraphs' => false,
            'nonationinfo' => false,
            'noartrest' => false,
            'teamgame' => false,
            'storyevents' => 0,
            'globals' => 5,
            'clustered' => false,
            'norandres' => false,
            'requiredap' => ($this->lvl1thrones+($this->lvl2thrones*2)+($this->lvl3thrones*3))-1,
        ];

        $ret = [];
        foreach ($this->toArray() as $key => $value)
        {
            if (array_key_exists($key, $defaultvalues) && $defaultvalues[$key] != $value)
                $ret[$key] = $value;   
        }
        return $ret;
    }

    public function getUINonDefaultSettings()
    {
        $values = $this->getNonDefaultSettings();

        if ($this->victorycond == 1)
        {
            $values['requiredap'] = $values['requiredap'] . ' (out of '.($this->lvl1thrones+($this->lvl2thrones*2)+($this->lvl3thrones*3)).')';
            $values['thrones'] = '1P: '.$this->lvl1thrones.', 2P: '.$this->lvl2thrones.', 3P: '.$this->lvl3thrones;
        }

        return $values;
    }

    /**
     * Get the command line string for this game, first host after pretender picking
     *
     * @return string
     */
    public function getCommandLine()
    {
        switch ($this->state)
        {
        	
            case 0:
            case 1:
            case 2:
                return $this->getCommandLinePretenderUpload();
                break;
            case 3:
                return $this->getCommandLineInitial();
                break;
            case 4:
            case 5:
                return $this->getCommandLineShort();
                break;
            default:
            	Log::info('game '.$this->name.' fell back to default on getCommandLine. State was: '.$this->state);
            	return $this->getCommandLineInitial();
            	break;
        }

    }

    public function getOptions()
    {
        $cmdline = [];

        foreach ($this->getNonDefaultSettings() as $key => $value)
        {
            switch ($key)
            {
                case 'magicsites':
                case 'hofsize':
                case 'eventrarity':
                case 'richness':
                case 'research':
                case 'supplies':
                case 'resources':
                case 'indepstr':
                case 'cataclysm':
                case 'globals':
                case 'startprov':
                	if (!empty($value))
                    	$cmdline[] = "--$key ".escapeshellarg($value);
                    break;
                case 'nonationinfo':
                case 'noartrest':
                case 'clustered':
                case 'teamgame':
                case 'capitalvp':
                case 'renaming':
                case 'norandres':
                case 'summervp':
                    $cmdline[] = "--$key";
                    break;
                case 'scoregraphs':
                	break;
                case 'storyevents':
                    if ($value === 0)
                        $cmdline[] = '--nostoryevents';
                    if ($value === 1)
                        $cmdline[] = '--storyevents';
                    if ($value === 2)
                        $cmdline[] = '--allstoryevents';
                    break;
            }
        }


        if (count($this->mods) > 0)
        {
            foreach ($this->orderedMods() as $mod)
            {
                $cmdline[] = '-M '.escapeshellarg($mod->filename);
            }
        }

        if (count($this->nationrules) > 0)
        {
            foreach ($this->nationrules as $rule)
            {
                switch ($rule->type)
                {
                    case NationRule::NATION_CLOSED:
                        $cmdline[] = '--closed '.$rule->nation_id;
                        break;
                    case NationRule::NATION_AI_EASY:
                        $cmdline[] = '--easyai '.$rule->nation_id;
                        break;
                    case NationRule::NATION_AI_NORMAL:
                        $cmdline[] = '--normai '.$rule->nation_id;
                        break;
                    case NationRule::NATION_AI_DIFFICULT:
                        $cmdline[] = '--diffai '.$rule->nation_id;
                        break;
                    case NationRule::NATION_AI_MIGHTY:
                        $cmdline[] = '--mightyai '.$rule->nation_id;
                        break;
                    case NationRule::NATION_AI_MASTER:
                        $cmdline[] = '--masterai '.$rule->nation_id;
                        break;
                    case NationRule::NATION_AI_IMPOSSIBLE:
                        $cmdline[] = '--impai '.$rule->nation_id;
                        break;
                    case NationRule::NATION_TEAM_PRETENDER:
                        $cmdline[] = '--team '.$rule->nation_id.' '.$rule->team.' 1';
                        break;
                    case NationRule::NATION_TEAM_DISCIPLE:
                        $cmdline[] = '--team '.$rule->nation_id.' '.$rule->team.' 2';
                        break;
                }
            }
        }

        switch ($this->victorycond)
        {
            case 0:
                $cmdline[] = '--conqall';
                break;
            case 1:
                $cmdline[] = '--thrones '.implode(' ', [(int)$this->lvl1thrones,(int)$this->lvl2thrones,(int)$this->lvl3thrones]);
                if (!empty($this->requiredap))
                    $cmdline[] = '--requiredap '.escapeshellarg($this->requiredap);
                break;
            default:
                break;
        }

        return $cmdline;

    }
    /**
     * Get the command line string for this game, first host after pretender picking
     *
     * @return string
     */
    public function getCommandLineInitial()
    {
        $cmdline = [
            '-T',
            '-S', 
            '--port '.(30000+$this->id),
            '--era '.$this->era,
            '--uploadtime 1',
            '--noclientstart',
            '--preexec '.escapeshellarg('php /home/dom5snek/snek/artisan games:parsestats '.$this->id),
            '--statfile',
            '--scoredump',
            '--statuspage /home/dom5snek/dominions5/savedgames/'.$this->shortname.'/status.html',
            '--mapfile '.escapeshellarg($this->map->filename),
             ];

	if (!empty($this->masterpw))
	    $cmdline[] = '--masterpass '.escapeshellarg($this->masterpw);

        if ($this->hours > 0)
            $cmdline[] = '--hours '.$this->hours;

        $cmdline = array_merge($cmdline, $this->getOptions());

        $cmdline[] = escapeshellarg($this->shortname);

        return implode(' ', $cmdline);

    }

    public function getCommandLineShort()
    {
        $cmdline = [
            '-T',
            '-S', 
            '--port '.(30000+$this->id),
            '--era '.$this->era,
            '--mapfile '.escapeshellarg($this->map->filename),
            '--preexec '.escapeshellarg('php /home/dom5snek/snek/artisan games:parsestats '.$this->id),
            '--statfile',
            '--scoredump',
            '--statuspage /home/dom5snek/dominions5/savedgames/'.$this->shortname.'/status.html'
            ];

	if (!empty($this->masterpw))
	    $cmdline[] = '--masterpass '.escapeshellarg($this->masterpw);

        if ($this->hours > 0)
            $cmdline[] = '--hours '.$this->hours;

        $cmdline = array_merge($cmdline, $this->getOptions());

        $cmdline[] = escapeshellarg($this->shortname);

        return implode(' ', $cmdline);
    }

    public function getCommandLinePretenderUpload()
    {
        $cmdline = [
            '-T',
            '-S', 
            '--era '.$this->era,
            '--port '.(30000+$this->id),
            '--noclientstart',
            '--mapfile '.escapeshellarg($this->map->filename),
            '--preexec '.escapeshellarg('php /home/dom5snek/snek/artisan games:parsestats '.$this->id),
            '--statfile',
            '--scoredump',
            '--statuspage /home/dom5snek/dominions5/savedgames/'.$this->shortname.'/status.html'
            ];

	if (!empty($this->masterpw))
	    $cmdline[] = '--masterpass '.escapeshellarg($this->masterpw);

        $cmdline = array_merge($cmdline, $this->getOptions());

        $cmdline[] = escapeshellarg($this->shortname);

        return implode(' ', $cmdline);
    }

    public function getUnitFile()
    {
        return '[Unit]
Description=SnekServer - '.$this->name.'

[Service]
ExecStart=/opt/dominions5/dom5.sh '.$this->getCommandLine();
    }
}
