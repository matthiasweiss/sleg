<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;
use App\Exceptions\UserAlreadyInTeam;
use Illuminate\Database\QueryException;

class MembershipsController extends Controller
{
    /**
     * Store a new membership.
     *
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function store(Team $team)
    {
        abort_unless(auth()->id() == $team->owner_id, 401);

        try {
            // try to add the user to the team
            $team->members()->attach(request(['user_id']));
        } catch (QueryException $e) {
            // if a query exception occurs the user is already in the team
            throw new UserAlreadyInTeam($team);
        }

        return redirect($team->path());
    }
}
