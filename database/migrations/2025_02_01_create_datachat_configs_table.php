<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datachat_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('api_key', 64)->unique();
            $table->string('widget_name')->default('DataChat');
            $table->string('primary_color', 7)->default('#3b82f6');
            $table->enum('position', ['bottom-right', 'bottom-left'])->default('bottom-right');
            $table->json('allowed_domains')->nullable();
            $table->integer('max_messages_per_day')->default(100);
            $table->integer('max_messages_per_minute')->default(10);
            $table->text('greeting_message')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('user_id');
            $table->index('api_key');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datachat_configs');
    }
};