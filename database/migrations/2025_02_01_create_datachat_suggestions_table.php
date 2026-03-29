<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datachat_suggestions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('config_id')->constrained('datachat_configs')->onDelete('cascade');
            $table->text('question');
            $table->string('category', 50)->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('config_id');
            $table->index(['config_id', 'is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datachat_suggestions');
    }
};