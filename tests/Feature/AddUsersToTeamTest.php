<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddUsersToTeamTest extends TestCase
{
    use RefreshDatabase;

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
