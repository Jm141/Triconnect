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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('recipient_type', ['all', 'teachers', 'parents']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('sent_by'); // userCode of the sender
            $table->enum('status', ['sent', 'read', 'archived'])->default('sent');
            $table->integer('recipient_count')->default(0);
            $table->timestamps();

            $table->index(['recipient_type', 'status']);
            $table->index(['priority', 'created_at']);
            $table->index('sent_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
