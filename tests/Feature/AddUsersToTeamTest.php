<?php

namespace Tests\Feature;

use App\User;
use App\Team;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddUsersToTeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function regular_users_cant_add_members_to_any_team()
    {
        $this->signIn();

        $randomTeam = Team::named('foobar')->foundedBy(factory(User::class)->create());

        $userToAdd = factory(User::class)->create(['name' => 'john']);

        $this->post("/memberships/{$randomTeam->id}", ['user_id' => $userToAdd->id])
        ->assertStatus(401);
    }

    /** @test */
    public function team_owners_can_add_other_members_to_the_team()
    {
        $this->signIn();

        $team = Team::named('bla')->foundedBy(auth()->user());

        $this->assertTrue($team->contains(auth()->user()));

        $userToAdd = factory(User::class)->create(['name' => 'john']);

        $this->post("/memberships/{$team->id}", ['user_id' => $userToAdd->id])
        ->assertRedirect($team->path());

        $this->assertTrue($team->fresh()->contains($userToAdd));
    }

    /** @test */
    public function team_owners_cant_add_the_same_user_twice()
    {
        $this->signIn();

        $team = Team::named('bla')->foundedBy(auth()->user());

        $userToAdd = factory(User::class)->create(['name' => 'john']);

        $this->post("/memberships/{$team->id}", ['user_id' => $userToAdd->id]);

        $this->post("/memberships/{$team->id}", ['user_id' => $userToAdd->id])
        ->assertRedirect($team->path())
        ->assertSessionHas('error');
    }
    /** @test */
    public function authenticated_users_can_search_for_other_users()
    {
        $this->signIn();

        $found = factory(User::class)->create([
            'name' => 'john'
        ]);

        $notFound = factory(User::class)->create([
            'name' => 'otherone'
        ]);

        $response = $this->json('GET', '/users/john')
            ->assertStatus(200)
            ->assertJson([[
                'name' => 'john'
            ]]);
    }
}
