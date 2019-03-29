<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Turing\Models\Product;

class ProductShowTest extends TestCase
{
    public function testQueryExistingProduct()
    {
        $id = 1;
        $product = \Turing\Models\Product::with('categories')->find($id);

        $response = $this->get('/api/products/' . $id);
        $response->assertStatus(200);
        $response->assertExactJson(['success' => true, 'product' => $product->toArray()]);
    }

    public function testQueryMissingProductAndGet404()
    {
        $response = $this->get('/api/products/' . PHP_INT_MAX);
        $response->assertStatus(404);
    }
}
