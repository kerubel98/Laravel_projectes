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
        // KPIs Table
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->foreignId('unit_id')->constrained()->onDelete('cascade'); // KPI belongs to a unit
            $table->string('measurement'); // e.g., Percentage, Number, Boolean
            $table->decimal('target_value', 10, 2); // Target value for the KPI
            $table->date('start_date'); // Start date of the KPI
            $table->date('end_date'); // End date of the KPI
            $table->timestamps();
        });

        // User-KPI Pivot Table (to track daily status)
        Schema::create('user_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User assigned to the KPI
            $table->foreignId('kpi_id')->constrained()->onDelete('cascade'); // KPI being tracked
            $table->date('tracking_date'); // Date of the status update
            $table->foreignId('status_id'); // Current value of the KPI on this date
            $table->text('notes')->nullable(); // Optional notes for the day
            $table->timestamps();
            $table->unique(['user_id', 'kpi_id', 'tracking_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_kpis');
        Schema::dropIfExists('kpis');
    }
};
