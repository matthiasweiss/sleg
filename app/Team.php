<?php

namespace App;

class Team extends Model
{
    /**
     * Always lazy load these relationships.
     *
     * @var array
     */
    protected $with = ['owner'];

    /**
     * Named constructor.
     *
     * @param string $name
     * @return \App\Team
     */
    public static function named($name)
    {
        return new static(compact('name'));
    }

    /**
     * Persist the founded team. (fluently called after the named constructor above)
     *
     * @param User $user
     * @return \App\Team
     */
    public function foundedBy(User $user)
    {
        return tap($this->fill(['owner_id' => $user->id]))->save();
    }

    /**
     * Return the owner of the team.
     *
     * @return \App\User
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
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

    /**
     * Check if the team contains the given user.
     *
     * @param User $user
     * @return boolean
     */
    public function contains(User $user)
    {
        return $this->owner->id == $user->id || $this->members->contains(function ($member) use ($user) {
            return $member->id == $user->id;
        });
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
