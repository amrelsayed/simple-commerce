<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $category1 = Category::factory()->create(['name' => 'Electronics']);
        $category2 = Category::factory()->create(['name' => 'Books']);

        Product::factory()->create([
            'name' => 'iPhone',
            'price' => 1000,
            'stock' => 50,
            'category_id' => $category1->id
        ]);

        Product::factory()->create([
            'name' => 'Samsung Galaxy',
            'price' => 900,
            'stock' => 30,
            'category_id' => $category1->id
        ]);

        Product::factory()->create([
            'name' => 'Harry Potter',
            'price' => 20,
            'stock' => 100,
            'category_id' => $category2->id
        ]);
    }

    public function test_can_filter_products_by_name()
    {
        $response = $this->json('GET', '/api/products', ['name' => 'iPhone']);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'iPhone',
                'price' => 1000,
                'stock' => 50,
            ]);
    }

    public function test_can_filter_products_by_price_range()
    {
        $response = $this->json('GET', '/api/products', ['price_from' => 100, 'price_to' => 1000]);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['name' => 'iPhone', 'stock' => 50])
            ->assertJsonFragment(['name' => 'Samsung Galaxy', 'stock' => 30]);
    }

    public function test_can_filter_products_by_category()
    {
        $category = Category::where('name', 'Electronics')->first();

        $response = $this->json('GET', '/api/products', ['category_id' => $category->id]);

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['name' => 'iPhone', 'stock' => 50])
            ->assertJsonFragment(['name' => 'Samsung Galaxy', 'stock' => 30]);
    }
    public function test_can_paginate_the_products()
    {
        $response = $this->json('GET', '/api/products');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'stock',
                        'category' => [
                            'id',
                            'name',
                        ],
                    ]
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => ['current_page', 'last_page', 'from', 'to', 'per_page', 'total'],
            ]);
    }

}
