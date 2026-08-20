<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('threat_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source_ip')->nullable();
            $table->integer('score')->default(0);
            $table->json('breakdown')->nullable();
            $table->string('risk_level');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_updated_at')->useCurrent();
            $table->timestamps();

            $table->index('source_ip');
            $table->index('score');
            $table->index('risk_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('threat_scores');
    }
};
