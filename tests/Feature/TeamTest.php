<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use Tests\TestCase;
use App\Exceptions\UserAlreadyInTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function guest_users_cant_create_teams()
    {
        $this->post('/teams', factory(Team::class)->make()->toArray())
            ->assertRedirect('/login');
    }

    /** @test */
    function signed_in_users_can_create_teams()
    {
        $this->signIn();

        $this->post('/teams', factory(Team::class)->make(['name' => 'foobar'])->toArray())
            ->assertRedirect('/teams/1');

        $this->assertCount(1, Team::all());
    }

    /** @test */
    function regular_users_cant_add_members_to_any_team()
    {
        $this->signIn();

        $randomTeam = factory(Team::class)->create(['owner_id' => 999]);

        $userToAdd = factory(User::class)->create(['name' => 'john']);

        $this->post("/memberships/{$randomTeam->id}", ['user_id' => $userToAdd->id])
            ->assertStatus(401);
    }

    /** @test */
    function team_owners_can_add_other_members_to_the_team()
    {
        $this->signIn();

        $team = factory(Team::class)->create(['owner_id' => auth()->id()]);
        $this->assertTrue($team->contains(auth()->user()));

        $userToAdd = factory(User::class)->create(['name' => 'john']);

        $this->post("/memberships/{$team->id}", ['user_id' => $userToAdd->id])
            ->assertRedirect($team->path());

        $this->assertTrue($team->contains($userToAdd));
    }

    /** @test */
    function team_owners_cant_add_the_same_user_twice()
    {
        $this->signIn();

        $team = factory(Team::class)->create(['owner_id' => auth()->id()]);

        $userToAdd = factory(User::class)->create(['name' => 'john']);

        $this->post("/memberships/{$team->id}", ['user_id' => $userToAdd->id]);

        $this->post("/memberships/{$team->id}", ['user_id' => $userToAdd->id])
            ->assertRedirect($team->path())
            ->assertSessionHas('error');
    }
}
