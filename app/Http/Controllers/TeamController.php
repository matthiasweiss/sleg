<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function show(Team $team)
    {
        abort_unless($team->contains(auth()->user()), 401);

        return view('teams.show', compact('team'));
    }

    /**
     * Store a new Team.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->validate([
            'name' => 'required'
        ]);

        $team = Team::named(request('name'))
            ->foundedBy(auth()->user());

        return redirect($team->path());
    }
}
