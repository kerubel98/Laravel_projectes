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
        // Statuses Table
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Pending, Completed, Under Investigation
            $table->string('color')->nullable();
            $table->timestamps();
        });

        // Labels Table
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Task, Issue, High Priority
            $table->text('description')->nullable();
            $table->text('solution')->nullable();
            $table->string('action_to_follow')->nullable(); // Fixed column name (no spaces)
            $table->timestamps();
        });

        // Tasks Table
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('status_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('set null');
            $table->datetime('due_date')->nullable();
            $table->foreignId('mailbox_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('tasks')->onDelete('cascade');
            $table->datetime('investigation_concluded_at')->nullable(); // Fixed typo: "investigation" → "investigation"
            $table->timestamps();
        });

        // Task Assignments (Pivot Table)
        Schema::create('task_user', function (Blueprint $table) {
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->primary(['task_id', 'user_id']);
            $table->timestamps();
        });

        // Task Labels (Pivot Table)
        Schema::create('label_task', function (Blueprint $table) {
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('label_id')->constrained()->onDelete('cascade');
            $table->primary(['task_id', 'label_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_task');
        Schema::dropIfExists('task_user');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('labels');
        Schema::dropIfExists('statuses');
    }
};
