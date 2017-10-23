<?php

namespace App\Http\Controllers;

use App\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Store a new Team.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $team = Team::named(request('name'))
            ->foundedBy(auth()->user());

        return redirect($team->path());
    }
}
