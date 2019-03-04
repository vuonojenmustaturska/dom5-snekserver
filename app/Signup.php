<?php

namespace Snek;

use Illuminate\Database\Eloquent\Model;

class Signup extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'signups';

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
    protected $fillable = ['user_id', 'lobby_id', 'nation_id'];


     /**
     * The user
     */
    public function user()
    {
        return $this->belongsTo('Snek\User');
    }

    /**
     * The lobby
     */
    public function lobby()
    {
        return $this->belongsTo('Snek\Lobby');
    }

    public function game()
    {
    	return $this->lobby->game();
    }

    public function nation()
    {
        return $this->game->nationById($this->nation_id);
    }
}
