<?php

namespace Snek;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\NullableFields;

use Snek\Nation;
use Snek\Game;
use Log;

class GameStat extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gamestats';


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
    protected $fillable = ['game_id', 'turn', 'nation_id', 'provinces', 'forts', 'income', 'gemincome', 'dominion', 'armysize', 'victorypoints', 'turn_status'];


    public function getNationAttribute()
    {
        //Log::info('Gamestat nation:'. $this->nation_id);
        return $this->game->nationById($this->nation_id);
        //return $this->belongsTo('Snek\Nation', 'nation_id');
    }

    public function game()
    {
        return $this->belongsTo('Snek\Game', 'game_id');
    }

}