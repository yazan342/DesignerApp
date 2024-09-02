<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('design_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('size_id')->constrained('sizes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('design_id')->constrained('designs')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_sizes');
    }
};
