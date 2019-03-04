<?php

namespace Snek;

use Illuminate\Database\Eloquent\Model;

class Lobby extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lobbies';

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
    protected $fillable = ['name', 'description', 'server_address', 'server_port', 'maxplayers', 'status', 'game_id'];


    /**
     * The signups for this lobby
     */
    public function signups()
    {
        return $this->hasMany('Snek\Signup', 'lobby_id');
    }


    public function game()
    {
        return $this->belongsTo('Snek\Game');
    }

    public function owner()
    {
        return $this->game->user();
    }

    public function getTurnAttribute()
    {
    	$t = $this->game->stats()->max('turn');
    	if ($t < 1)
    		return 0;
    	else
    		return $t;
    }

    public function currentturnstats()
    {
    	return $this->game->stats()->where('turn', $this->turn);
    }

    public function lobbystats()
    {
        $stats = $this->currentturnstats;
        $nations = [];
        foreach ($stats as $stat)
        {
            $nations[] = $stat->nation['nation_id'];
        }

        $nationsToBeAdded = [];
        foreach ($this->signups as $signup)
        {
            if (!in_array($signup->nation_id, $nations))
            {
                $nationsToBeAdded[] = (object)[
                        'nation' => $signup->nation(),
                        'turn_status' => '-'
                    ];
            }
        }

        return array_merge($stats->toArray(), $nationsToBeAdded);
    }
}
