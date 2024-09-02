<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Design;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'design_id' => Design::factory(),
            'rate' => $this->faker->randomFloat(1, 1, 5),
        ];
    }
}
