<?php

namespace Tests\Unit;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_knows_its_path()
    {
        $team = factory(Team::class)->create();

        $this->assertEquals('/teams/1', $team->path());
    }

    /** @test */
    function it_knows_its_owner()
    {
        $user = factory(User::class)->create(['name' => 'john']);
        $team = factory(Team::class)->create(['owner_id' => $user->id]);

        $this->assertEquals($team->owner->name, 'john');
    }
}
