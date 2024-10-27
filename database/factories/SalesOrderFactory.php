<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SalesOrder>
 */
class SalesOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'total_amount' => $this->faker->randomFloat(2, 1, 1000),
            'customer_id' => Customer::inRandomOrder()->value('id'),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
