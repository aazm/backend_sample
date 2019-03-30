<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ShoppingCartTest extends TestCase
{
    public function testPutShoppingCartWithOutAuthFails()
    {
        $response = $this->put('/api/cart');
        $response->assertStatus(401);
        $response->assertJsonStructure(['success']);

    }

    public function testMissingProductAddingFails()
    {
        $user = factory(\Turing\User::class)->create();

        $response = $this->put('/api/cart', [
            'product_id' => PHP_INT_MAX,
            'quantity' => rand(1, 10),
            'buy_now' => rand(0,1),
        ],['HTTP_Authorization' => 'Bearer '.JWTAuth::fromUser($user)]);

        $response->assertStatus(400);
        $response->assertJsonStructure(['success', 'errors' => ['product_id']]);

        \Turing\User::where(['email' => $user->email])->delete();
    }

    public function testSuccessfulAddCartPresentInResponse()
    {
        $user = factory(\Turing\User::class)->create();
        $collection = \Turing\Models\Product::all();

        $response = $this->put('/api/cart', [
            'product_id' => $collection->random()->getKey(),
            'quantity' => rand(1, 10),
            'buy_now' => rand(0,1),
            'attributes' => 'some string',
        ],['HTTP_Authorization' => 'Bearer '.JWTAuth::fromUser($user)]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'cart']);

        $user->delete();
    }
}
