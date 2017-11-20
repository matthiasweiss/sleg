<?php

namespace App;

class Team extends Model
{
    /**
     * Always lazy load these relationships.
     *
     * @var array
     */
    protected $with = ['owners', 'members'];

    /**
     * Named constructor.
     *
     * @param string $name
     * @return \App\Team
     */
    public static function named($name)
    {
        return static::create(compact('name'));
    }

    /**
     * Persist the founded team. (fluently called after the named constructor above)
     *
     * @param User $user
     * @return \App\Team
     */
    public function foundedBy(User $user)
    {
        Membership::create([
            'user_id' => $user->id,
            'team_id' => $this->id,
            'is_owner' => true
        ]);

        return $this;
    }

    /**
     * Return the owners of the team.
     *
     * @return \App\User
     */
    public function owners()
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->where('is_owner', true);
    }

    /**
     * Return all the members of the team.
     *
     * @return \App\Membership
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'memberships');
    }

    public function addMember($user)
    {
        $user = $user instanceof User ? $user->id : $user;

        return $this->members()->attach($user);
    }

    /**
     * Remove the given user from this team.
     *
     * @param int $userId
     * @return Team
     */
    public function remove($userId)
    {
        if ($this->contains($userId)) {
            Membership::where([
                'user_id' => $userId,
                'team_id' => $this->id
            ])->delete();
        }

        return $this;
    }

    /**
     * Check if the team contains the given user.
     *
     * @param User $user
     * @return boolean
     */
    public function contains($user)
    {
        $user = $user instanceof User ? $user->id : $user;
        return $this->owners
            ->merge($this->members)
            ->pluck('id')
            ->contains($user);
    }

    /**
     * The http path to access the current team.
     *
     * @return string
     */
    public function path()
    {
        return "/teams/{$this->id}";
    }
}
