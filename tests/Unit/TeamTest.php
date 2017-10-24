<?php

namespace Tests\Unit;

use App\Team;
use App\User;
use Tests\TestCase;
use App\Membership;
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
        $team = Team::named('foo')->foundedBy($user);

        $this->assertEquals($team->owners->first()->name, 'john');
    }

    /** @test */
    function it_knows_which_people_are_in_the_team()
    {
        $owner = factory(User::class)->create();
        $member = factory(User::class)->create();
        $random = factory(User::class)->create();

        $team = Team::named('foo')->foundedBy($owner);
        $team->addMember($member);

        $this->assertCount(2, $team->members);

        $this->assertTrue($team->contains($owner));
        $this->assertTrue($team->contains($member));
        $this->assertFalse($team->contains($random));
    }
}
