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
        Schema::create('sensor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('component_name');
            $table->decimal('recorded_voltage', 8, 2);
            $table->boolean('is_anomaly')->default(false);
            $table->enum('status', ['Pass', 'Fail'])->default('Pass');
            $table->timestamps();
            $table->softDeletes();

            // Composite index for efficient queries by vehicle_id and component_name
            $table->index(['vehicle_id', 'component_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_logs');
    }
};
