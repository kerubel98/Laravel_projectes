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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('solution');
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('cases_task', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Task::class);
            $table->foreignIdFor(\App\Models\Cases::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
        Schema::dropIfExists('cases_task');

    }
};
