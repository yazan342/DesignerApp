<?php

namespace Database\Factories;

use App\Models\Design;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class DesignFactory extends Factory
{
    protected $model = Design::class;

    public function definition(): array
    {
        return [
            'designer_id' => User::factory(),
            'category_id' =>  Category::inRandomOrder()->first()->id,
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'prepare_duration' => $this->faker->numberBetween(1, 7) . ' days',
            'image' => $this->faker->imageUrl(640, 480, 'design'),
            'price' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
