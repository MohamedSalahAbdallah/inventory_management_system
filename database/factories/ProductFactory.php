<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Supplier;
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
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'sku' => $this->faker->randomNumber(6),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'image' => 'https://picsum.photos/800',

            'category_id' => Category::inRandomOrder()->value('id'),
            'supplier_id' => Supplier::inRandomOrder()->value('id'),
        ];
    }
}
