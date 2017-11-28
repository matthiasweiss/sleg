<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RemoveUsersFromTeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function the_team_owner_can_remove_members()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $team = Team::named('foo')->foundedBy(auth()->user());

        $userToBeRemoved = factory(User::class)->create();
        $team->addMember($userToBeRemoved);

        $this->deleteJson("/memberships/{$team->id}", ['user_id' => $userToBeRemoved->id])
            ->assertStatus(200);

        $this->assertCount(1, $team->fresh()->members);
    }

    /** @test */
    public function a_user_who_does_not_own_the_team_cant_remove_members()
    {
        $owner = factory(User::class)->create();
        $userToBeRemoved = factory(User::class)->create();
        $randomUser = factory(User::class)->create();

        $team = Team::named('foo')->foundedBy($owner);

        $team->addMember($userToBeRemoved);
        $team->addMember($randomUser);

        $this->signIn($randomUser);

        $this->deleteJson("/memberships/{$team->id}", ['user_id' => $userToBeRemoved->id])
            ->assertStatus(401);

        $this->assertCount(3, $team->fresh()->members);
    }
}
