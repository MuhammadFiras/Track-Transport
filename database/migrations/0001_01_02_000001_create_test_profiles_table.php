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
        Schema::create('test_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_model');
            $table->string('component_name');
            $table->decimal('min_voltage', 8, 2);
            $table->decimal('max_voltage', 8, 2);
            $table->timestamps();

            // Composite index for efficient lookups by vehicle_model and component_name
            $table->index(['vehicle_model', 'component_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_profiles');
    }
};
