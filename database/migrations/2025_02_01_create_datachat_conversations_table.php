<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datachat_conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('config_id')->constrained('datachat_configs')->onDelete('cascade');
            $table->string('session_id', 64);
            $table->string('end_user_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('last_message_at');
            $table->integer('message_count')->default(0);

            $table->index('config_id');
            $table->index('session_id');
            $table->index('end_user_id');
            $table->index('last_message_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datachat_conversations');
    }
};