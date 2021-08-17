<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Product;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()->count(30)->create()->each(function ($product){
            $product->options()->attach(Option::factory()->count(3)->create(), [
                'value' => $this->faker->word,
            ]);
        });
    }
}
