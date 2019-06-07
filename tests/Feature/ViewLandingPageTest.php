<?php

namespace Tests\Feature;

use App\Product;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewLandingPageTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function landing_page_loads_correctly()
    {
        //Arrange

        //Act
        $response = $this->get('/');

        //Assert
        $response->assertStatus(200);
        $response->assertSee('Laravel Ecommerce');
        $response->assertSee('Includes multiple products');


    }

    /** @test */
    public function featured_product_is_visible()
    {
         //Arrange
          // creacion de usuario sin usar factory
        $featured = Product::create([
            'featured' => true,
            'name' => 'Laptop 1',
            'slug' => 'laptop-1',
            'details' => ' 13 inch, 15 TB SSD, 32GB RAM',
            'price' => 242053,
            'description' =>'Lorem 1 ipsum dolor sit amet, consectetur adipisicing elit. Ipsum temporibus iusto ipsa, asperiores voluptas unde aspernatur praesentium in? Aliquam, dolore!',
            'image' => 'products/dummy/laptop-1.jpg',
            'images' => '["products\/dummy\/laptop-2.jpg","products\/dummy\/laptop-3.jpg","products\/dummy\/laptop-4.jpg"]',
        ]);

        //Act
        $response = $this->get('/');

        //Assert
        $response->assertSee($featured->name);
        $response->assertSee("2420.53");


    }

    /** @test */
    public function notFeatured_product_is_visible()
    {
        //Arrange
            //crear usuario usando factory
            $notFeatured = factory(Product::class)->create([
                'featured' => false,
                'name' => 'Laptop 1',
                'price' => 149999,
            ]);

        //Act
        $response = $this->get('/');

        //Assert
        $response->assertDontSee($notFeatured->name);
        $response->assertDontSee("1499.99");


    }
}
