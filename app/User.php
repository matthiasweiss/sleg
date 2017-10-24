<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Determine if the current user is the owner of the given team.
     *
     * @param Team $team
     * @return boolean
     */
    public function owns(Team $team)
    {
        return $this->ownedTeams
            ->pluck('id')
            ->contains($team->id);
    }

    /**
     * Return all the teams the current user owns.
     *
     * @return \Illuminate\Support\Collection
     */
    public function ownedTeams()
    {
        return $this->teams()
            ->where('is_owner', true);
    }

    /**
     * A user may be in many teams.
     *
     * @return \Illuminate\Support\Collection
     */
    public function teams()
    {
        return $this
            ->belongsToMany(Team::class, 'memberships');
    }
}
