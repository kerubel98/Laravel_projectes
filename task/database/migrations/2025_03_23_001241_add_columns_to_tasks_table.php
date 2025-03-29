<?php

use App\Enum\TaskPriority;
use App\Enum\TaskStatus;
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
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('status', TaskStatus::values())->default(TaskStatus::TODO->value)->after('description');
            $table->enum('priority', TaskPriority::values())->default(TaskPriority::MEDIUM->value)->after('status');
            $table->dateTime('due_date')->nullable()->after('priority');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->after('due_date');
            $table->text('notes')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->dateTime('completed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
