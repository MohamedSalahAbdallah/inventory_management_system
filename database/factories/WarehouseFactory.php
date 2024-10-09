<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $egyptianCities = [
            'Cairo',
            'Alexandria',
            'Giza',
            'Port Said',
            'Suez',
            'Luxor',
            'Asyut',
            'Mansoura',
            'Tanta',
            'Ismailia',
            'Faiyum',
            'Zagazig',
            'Damietta',
            'Minya'
        ];
        return [
            'name' => $this->faker->firstNameMale(),
            'location' => $this->faker->randomElement($egyptianCities),
            'total_capacity' => $this->faker->randomNumber(3, true)
        ];
    }
}
