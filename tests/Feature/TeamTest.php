<?php

namespace Tests\Feature;

use App\Team;
use App\User;
use Tests\TestCase;
use App\Exceptions\UserAlreadyInTeam;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function guests_cant_see_any_team_pages()
    {
        $team = factory(Team::class)->create();

        $this->get($team->path())
            ->assertRedirect('/login');
    }

    /** @test */
    function logged_in_users_cant_access_a_teams_page_if_they_arent_part_of_the_team()
    {
        $this->signIn();

        $team = Team::named('foo')->foundedBy(factory(User::class)->create());

        $this->get($team->path())
            ->assertStatus(401);
    }

     /** @test */
    public function guest_users_cant_create_teams()
    {
        $this->post('/teams', factory(Team::class)->make()->toArray())
            ->assertRedirect('/login');
    }

    /** @test */
    function a_name_is_necessary_in_order_to_create_a_team()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $this->expectException(ValidationException::class);

        $this->post('/teams');

        $this->assertCount(0, Team::all());
    }

    /** @test */
    function signed_in_users_can_create_teams()
    {
        $this->signIn();

        $this->post('/teams', ['name' => 'foobar'])
            ->assertRedirect('/teams/1');

        $this->assertCount(1, Team::all());
    }

    /** @test */
    function team_members_can_access_the_team_page()
    {
        // $this->withoutExceptionHandling();
        $this->signIn();

        $team = factory(Team::class)->create();

        $team->addMember(auth()->user());

        $this->get($team->path())
            ->assertStatus(200)
            ->assertSee($team->name);
    }

    /** @test */
    function regular_users_cant_add_members_to_any_team()
    {
        $this->signIn();

        $randomTeam = Team::named('foobar')->foundedBy(factory(User::class)->create());

        $userToAdd = factory(User::class)->create(['name' => 'john']);

        $this->post("/memberships/{$randomTeam->id}", ['user_id' => $userToAdd->id])
            ->assertStatus(401);
    }

    /** @test */
    function team_owners_can_add_other_members_to_the_team()
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
    function team_owners_cant_add_the_same_user_twice()
    {
        $this->signIn();

        $team = Team::named('bla')->foundedBy(auth()->user());

        $userToAdd = factory(User::class)->create(['name' => 'john']);

        $this->post("/memberships/{$team->id}", ['user_id' => $userToAdd->id]);

        $this->post("/memberships/{$team->id}", ['user_id' => $userToAdd->id])
            ->assertRedirect($team->path())
            ->assertSessionHas('error');
    }
}
