<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSubmitEmptyFormFails()
    {
        $response = $this->post('/api/registration', []);
        $response->assertStatus(400);
        $response->assertExactJson([
            'success' => false,
            'errors' => ['email' => ['The email field is required.'], 'name' => ['The name field is required.'], 'password' => ['The password field is required.']]
        ]);
    }

    public function testSubmitSuccessGetToken()
    {
        $email = $this->faker()->email;
        $response = $this->post('/api/registration', [
            'name' => $this->faker()->name(),
            'email' => $email,
            'password' => $this->faker()->password()
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'access_token', 'expires_in']);

        \Turing\User::where('email', $email)->delete();
    }

    public function testSubmitMinLenPasswordFails()
    {
        $email = $this->faker()->email;
        $response = $this->post('/api/registration', [
            'name' => $this->faker()->name(),
            'email' => $email,
            'password' => $this->faker()->password(0,2)
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure(['success', 'errors' => ['password']]);
    }


}
