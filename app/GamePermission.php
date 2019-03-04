<?php

namespace Snek;

use Illuminate\Database\Eloquent\Model;

use Snek\Game;
use Snek\User;

class GamePermission extends Model
{
    const PERMISSION_NONE = 0;
    const PERMISSION_RESTART = 1;
    const PERMISSION_START = 2;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gamepermissions';

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
    protected $fillable = ['game_id', 'user_id', 'permission'];


    public function game()
    {
    	return $this->belongsTo('Snek\Game');
    }

    public function user()
    {
        return $this->belongsTo('Snek\User');
    }

    public static function canRestart(Game $game, User $user)
    {
        if ($user->is_admin)
            return true;
        
        if ($game->user->id == $user->id)
            return true;

        if (GamePermission::where('game_id', $game->id)->where('user_id', $user->id)->where('permission', GamePermission::PERMISSION_RESTART)->count() > 0)
            return true;

        return false;
    }

}
