<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerTest extends TestCase
{
    use WithFaker;

    public function testShowUserProfileForUserWithToken()
    {
        $user = factory(\Turing\User::class)->create();
        $response = $this->get('/api/customer', ['HTTP_Authorization' => 'Bearer '.JWTAuth::fromUser($user)]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'customer']);

        \Turing\User::where(['email' => $user->email])->delete();

    }

    public function testShowUserProfileForUserWithoutToken()
    {
        $response = $this->get('/api/customer');
        $response->assertStatus(401);
        $response->assertJsonStructure(['success']);
    }

    public function testUpdateCustomerWithEmptyArrayMissingNameResponse()
    {
        $user = factory(\Turing\User::class)->create();
        $response = $this->put('/api/customer', [], ['HTTP_Authorization' => 'Bearer '.JWTAuth::fromUser($user)]);
        $response->assertStatus(400);
        $response->assertJsonStructure(['success', 'errors' => ['name']]);

        \Turing\User::where(['email' => $user->email])->delete();

    }

    public function testSuccessfulUpdateCustomerPresentInResponse()
    {
        $user = factory(\Turing\User::class)->create();
        $response = $this->put('/api/customer', ['name' => $this->faker()->name], ['HTTP_Authorization' => 'Bearer '.JWTAuth::fromUser($user)]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'customer']);

        \Turing\User::where(['email' => $user->email])->delete();

    }


}
