<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });


        DB::table('categories')->insert([
            ['name' => 'Men\'s Clothing'],
            ['name' => 'Women\'s Clothing'],
            ['name' => 'Bags'],
            ['name' => 'Kid\'s Clothing'],
            ['name' => 'Hats'],
            ['name' => 'Accessories'],
        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
