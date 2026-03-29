<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datachat_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('config_id')->constrained('datachat_configs')->onDelete('cascade');
            $table->date('date');
            $table->integer('message_count')->default(0);
            $table->decimal('ai_api_cost', 10, 4)->default(0);
            $table->timestamps();

            $table->unique(['config_id', 'date']);
            $table->index(['config_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datachat_usage');
    }
};