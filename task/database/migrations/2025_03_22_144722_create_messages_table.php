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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('zoho_message_id')->unique();
            $table->string('subject');
            $table->string('sender');
            $table->text('content');
            $table->timestamp('received_at');
            $table->text('to_address')->nullable();
            $table->text('cc_address')->nullable();
            $table->unsignedTinyInteger('priority')->default(3);
            $table->string('thread_id')->nullable();
            $table->string('flag_id')->default('flag_not_set');
            $table->enum('recipient_type', ['to', 'cc', 'bcc'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
