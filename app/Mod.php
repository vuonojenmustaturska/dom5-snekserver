<?php

namespace Snek;

use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mods';

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
    protected $fillable = ['name', 'description', 'filename', 'version', 'icon', 'user_id', 'disablesoldnations'];


    /**
     * The games using this mod
     */
    public function games()
    {
        return $this->belongsToMany('Snek\Game');
    }

    public function user()
    {
        return $this->belongsTo('Snek\User');
    }
}
