<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use WithFaker;

    public function testSuccessfulLogin()
    {
        $password = $this->faker()->password();
        $user = factory(\Turing\User::class)->create(['password' => bcrypt($password)]);

        $response = $this->post('/api/auth/login', ['email' => $user->email, 'password' => $password]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token', 'token_type','expires_in']);

        \Turing\User::where('email', $user->email)->delete();
    }

    public function testFailedLogin()
    {
        $password = $this->faker()->password();
        $user = factory(\Turing\User::class)->create(['password' => bcrypt($password)]);

        $response = $this->post('/api/auth/login', ['email' => $user->email, 'password' => 'not_my_password']);
        $response->assertStatus(400);

        $response->assertJsonStructure(['success', 'errors' => ['email']]);

        \Turing\User::where('email', $user->email)->delete();
    }

    public function testMissingEmailRespondsError()
    {
        $password = $this->faker()->password();
        $user = factory(\Turing\User::class)->create(['password' => bcrypt($password)]);

        $response = $this->post('/api/auth/login', ['password' => 'not_my_password']);
        $response->assertStatus(400);

        $response->assertJsonStructure(['success', 'errors' => ['email']]);

        \Turing\User::where('email', $user->email)->delete();

    }

    public function testMissingPasswordRespondsError()
    {
        $password = $this->faker()->password();
        $user = factory(\Turing\User::class)->create(['password' => bcrypt($password)]);

        $response = $this->post('/api/auth/login', ['email' => $user->email]);
        $response->assertStatus(400);

        $response->assertJsonStructure(['success', 'errors' => ['password']]);

        \Turing\User::where('email', $user->email)->delete();

    }
}
