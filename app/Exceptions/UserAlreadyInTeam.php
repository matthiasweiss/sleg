<?php

namespace App\Exceptions;

use App\Team;
use Exception;

class UserAlreadyInTeam extends Exception
{
    protected $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * Render the exception (redirect)
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return redirect($this->team->path())
            ->with('error', 'User is already in this team!');
    }
}
