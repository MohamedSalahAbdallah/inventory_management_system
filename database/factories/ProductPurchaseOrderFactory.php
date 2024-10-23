<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\WarehouseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPurchaseOrder>
 */
class ProductPurchaseOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'product_id' => Product::inRandomOrder()->value('id'),
            'purchase_order_id' => PurchaseOrder::inRandomOrder()->value('id'),
            'warehouse_section_id' => WarehouseSection::inRandomOrder()->value('id')
        ];
    }
}
