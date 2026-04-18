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
        Schema::create('qc_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->string('supervisor_name');
            $table->integer('total_tests');
            $table->integer('failed_tests');
            $table->enum('final_decision', ['Approved', 'Rejected'])->default('Rejected');
            $table->string('report_file_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index for querying reports by vehicle
            $table->index('vehicle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_reports');
    }
};
