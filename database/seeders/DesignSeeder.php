<?php

namespace Database\Seeders;

use App\Models\Design;
use App\Models\User;
use App\Models\Category;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Database\Seeder;

class DesignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designers = User::where('is_designer', true)->get();

        foreach ($designers as $designer) {
            $designs = Design::factory()->count(5)->create([
                'designer_id' => $designer->id,
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);

            foreach ($designs as $design) {
                $sizes = Size::inRandomOrder()->take(3)->pluck('id');
                $colors = Color::inRandomOrder()->take(3)->pluck('id');

                $design->sizes()->attach($sizes);
                $design->colors()->attach($colors);
            }
        }
    }
}
