<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\WarehouseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductWarehouse>
 */
class ProductWarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'warehouse_section_id' => WarehouseSection::inRandomOrder()->value('id'),
            'product_id' => Product::inRandomOrder()->value('id'),
            'quantity' => $this->faker->numberBetween(0, 300)
        ];
    }
}
