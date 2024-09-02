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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('hex');
            $table->timestamps();
        });

        DB::table('colors')->insert([
            ['hex' => '#FF5733'],
            ['hex' => '#33FF57'],
            ['hex' => '#3357FF'],
            ['hex' => '#F3F3F3'],
            ['hex' => '#000000'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
