<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // $faker = fake();
        return [
            // 'name' => $faker->name(),
            // 'description' => $faker->random,
            // 'type',
            // 'stock',
            // 'buying_price',
            // 'selling_price',
            // 'image_url',
        ];
    }
}
