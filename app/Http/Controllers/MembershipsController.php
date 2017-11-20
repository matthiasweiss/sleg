<?php

namespace App\Http\Controllers;

use App\Team;
use App\Membership;
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
        abort_unless(auth()->user()->owns($team), 401);

        try {
            // try to add the user to the team
            $team->addMember(request('user_id'));
        } catch (QueryException $e) {
            // if a query exception occurs the user is already in the team
            throw new UserAlreadyInTeam($team);
        }

        return redirect($team->path());
    }

    public function destroy(Team $team)
    {
        abort_unless($userId = request('user_id'), 422);
        abort_unless(auth()->user()->owns($team), 401);

        $team->remove($userId);
    }
}
