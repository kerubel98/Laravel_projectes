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
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#6b7280');
            $table->timestamps();
        });

        Schema::create('priorities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#6b7280');
            $table->timestamps();
        });

        Schema::create('issue_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('issue_categories')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('assignee_id')->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('priority_id')->constrained('priorities');
            $table->foreignId('category_id')->nullable()->constrained('issue_categories');
            $table->date('target_resolution_date')->nullable();
            $table->date('resolved_at')->nullable();
            $table->text('resolution_summary')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('assignee_id')->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('priority_id')->constrained('priorities');
            $table->foreignId('issue_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('due_date')->nullable();
            $table->date('completed_at')->nullable();
            $table->foreignId('mail_id')->nullable()->constrained('mails');
            $table->timestamps();
        });

        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('issue_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->text('comment');
            $table->timestamps();
        });

        Schema::create('issue_progress_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained();
            $table->text('update');
            $table->foreignId('status_id')->constrained('statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_progress_updates');
        Schema::dropIfExists('task_comments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('issues');
        Schema::dropIfExists('issue_categories');
        Schema::dropIfExists('priorities');
        Schema::dropIfExists('statuses');
    }
};
