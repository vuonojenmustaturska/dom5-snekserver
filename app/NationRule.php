<?php

namespace Snek;

use Iatstuti\Database\Support\NullableFields;

use Illuminate\Database\Eloquent\Model;

class NationRule extends Model
{
    use NullableFields;

    const NATION_CLOSED = 0;
    const NATION_AI_EASY = 10;
    const NATION_AI_NORMAL = 11;
    const NATION_AI_DIFFICULT = 12;
    const NATION_AI_MIGHTY = 13;
    const NATION_AI_MASTER = 14;
    const NATION_AI_IMPOSSIBLE = 15;
    const NATION_TEAM_PRETENDER = 20;
    const NATION_TEAM_DISCIPLE = 21;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'nationrules';

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
    protected $fillable = ['game_id', 'nation_id', 'type', 'team'];


    protected $nullable = [ 'team' ];


    public function game()
    {
    	return $this->belongsTo('Snek\Game');
    }

    public function nation()
    {
        return $this->game->nationById($this->nation_id);
    }
}
