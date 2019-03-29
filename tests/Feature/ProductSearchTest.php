<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductSearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testTotalPresentsAndEqualsInAnswer()
    {
        $count = \Turing\Models\Product::count();
        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertJsonFragment(['total' => $count]);
    }



    public function testUnrealisticOffsetReturns404()
    {
        $response = $this->get('/api/products?offset=' . PHP_INT_MAX);
        $response->assertStatus(404);
        $response->assertExactJson(['success' => false]);
    }

    public function testSearchNameFistChart()
    {
        $char = 'C';
        $builder = \Turing\Models\Product::where('name', 'like', $char.'%');
        $response = $this->get('/api/products?criteria[name]=' . $char);

        $response->assertStatus(200);
        $response->assertJsonFragment(['total' => $builder->count()]);

    }

    public function testSearchByDepartment()
    {
        $department = 1;
        $productCount = \Turing\Models\Product::whereHas('categories', function($q) use($department) { $q->where('department_id', $department); })->count();

        $response = $this->get('/api/products?criteria[department]=' . $department);
        $response->assertJsonFragment(['total' => $productCount]);

    }

    public function testSearchByMissingDepartment()
    {
        $response = $this->get('/api/products?criteria[department]=' . PHP_INT_MAX);
        $response->assertStatus(400);
        $response->assertExactJson(['success' => false, 'errors' => ['criteria.department' => ['Department does not exists']]]);

    }
    public function testSearchByMissingCategory()
    {
        $response = $this->get('/api/products?criteria[category]=' . PHP_INT_MAX);
        $response->assertStatus(400);
        $response->assertExactJson(['success' => false, 'errors' => ['criteria.category' => ['Category does not exists']]]);

    }

    public function testInvalidCombination()
    {
        $cat = \Turing\Models\Category::first();

        $response = $this->get('/api/products?criteria[department]=' . ($cat->department_id + 1) . '&criteria[category]=' . $cat->getKey());
        $response->assertStatus(400);
        $response->assertExactJson(['success' => false, 'errors' => ['criteria.category' => ['Invalid department&category combination']]]);
    }

}
