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
        // Migration File: create_emails_table
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('zoho_email_id')->unique(); // Zoho's unique email ID
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('body');
            $table->json('from'); // {name: string, email: string}
            $table->json('to')->nullable(); // Array of recipients
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->json('labels')->nullable(); // Zoho labels/categories
            $table->boolean('is_read')->default(false);
            $table->boolean('has_attachments')->default(false);
            $table->datetime('received_at');
            $table->datetime('sent_at');
            $table->text('headers')->nullable();
            $table->string('message_id')->unique(); // RFC 5322 message ID
            $table->string('thread_id')->nullable(); // Conversation thread ID
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'received_at']);
            $table->index('is_read');
            $table->index('thread_id');
        });

// Migration File: create_attachments_table
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->constrained()->onDelete('cascade');
            $table->string('zoho_attachment_id');
            $table->string('file_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            $table->string('storage_path');
            $table->text('content_id')->nullable(); // For inline images
            $table->timestamps();

            // Indexes
            $table->index(['email_id', 'zoho_attachment_id']);
        });

// Migration File: create_senders_table (Optional for analytics)
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->unsignedInteger('email_count')->default(0);
            $table->timestamps();
        });

// Migration File: create_email_sender_pivot_table
        Schema::create('email_sender', function (Blueprint $table) {
            $table->foreignId('email_id')->constrained();
            $table->foreignId('sender_id')->constrained();
            $table->primary(['email_id', 'sender_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inboxes');
    }
};
