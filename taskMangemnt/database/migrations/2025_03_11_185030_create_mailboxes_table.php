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
        // Mailboxes Table
        Schema::create('mailboxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who owns the mailbox
            $table->string('email')->unique(); // Email address of the mailbox
            $table->string('service_type'); // e.g., Gmail, Outlook
            $table->text('access_token'); // OAuth access token
            $table->text('refresh_token')->nullable(); // OAuth refresh token
            $table->datetime('expires_at')->nullable(); // Token expiration date
            $table->timestamps();
        });

        // Messages Table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mailbox_id')->constrained()->onDelete('cascade'); // Link to the mailbox
            $table->string('message_id')->unique(); // Unique ID from the email service
            $table->string('subject'); // Email subject
            $table->text('body'); // Email body
            $table->boolean('is_read')->default(false); // Whether the message has been read
            $table->string('from_email'); // Sender's email
            $table->string('from_name')->nullable(); // Sender's name
            $table->json('to')->nullable(); // Recipients (stored as JSON array)
            $table->json('cc')->nullable(); // CC recipients (stored as JSON array)
            $table->json('bcc')->nullable(); // BCC recipients (stored as JSON array)
            $table->json('attachments')->nullable(); // Attachments (stored as JSON array)
            $table->boolean('is_task')->default(false); // Whether the message is labeled as a task
            $table->foreignId('task_id')->nullable()->constrained('tasks')->onDelete('set null'); // Link to a task if labeled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('mailboxes');
    }
};
