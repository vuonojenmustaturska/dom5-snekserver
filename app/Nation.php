<?php

namespace Snek;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use Snek\Mod;
use Log;

class Nation extends Model
{
    use NullableFields;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'nations';


        /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $nullable = [
        'name',
        'implemented_by',
        'description',
        'brief',
        'epithet',
        'era'
    ];

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
    protected $fillable = ['name', 'description', 'brief', 'nation_id', 'implemented_by', 'era', 'epithet', 'flag'];


    public function mod()
    {
        return $this->belongsTo('Snek\Mod', 'implemented_by');
    }

    public static function getNationsByModsNames($modnames)
    {
        //Log::info('Modnames: '. implode(', ', $modnames));
        return Nation::getNationsByMods(Mod::whereIn('filename',$modnames)->get());

    }

    public static function getNationsByMods($mods)
    {

        $nations = [];
        $corenations = Nation::where('implemented_by', null)->get();

        foreach ($corenations as $nation)
            $nations[$nation->nation_id] = $nation->toArray();

        foreach ($mods as $mod)
        {
            //Log::info("Checking mod {$mod->name}");
            $modnations = Nation::where('implemented_by', $mod->id)->get();

            foreach ($modnations as $modnation)
            {
                if (array_key_exists($modnation->nation_id, $nations))
                    array_merge($nations[$modnation->nation_id], array_filter($modnation->toArray()));
                else
                    $nations[$modnation->nation_id] = $modnation->toArray();
            }
        }

        //Log::info(print_r($nations,true));

        return $nations;
    }
}