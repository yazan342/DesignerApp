<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use App\Models\Design;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'design_id' => Design::factory(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'color' => $this->faker->hexColor(),
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
        ];
    }
}
