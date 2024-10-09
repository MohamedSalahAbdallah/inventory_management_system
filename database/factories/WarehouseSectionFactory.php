<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WarehouseSection>
 */
class WarehouseSectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $capacity = $this->faker->numberBetween(50, int2: 2000);
        $emptySlots = $this->faker->numberBetween(0, $capacity);
        $reservedSlots = $capacity - $emptySlots;

        return [
            'warehouse_id' => Warehouse::inRandomOrder()->value('id'),
            'section_name'=> $this->faker->word(),
            'section_type' => $this->faker->randomElement(['refrigerator', 'shelves', 'other']),
            'capacity' => $capacity,
            'empty_slots' => $emptySlots,
            'reserved_slots' => $reservedSlots,
        ];
    }
}
