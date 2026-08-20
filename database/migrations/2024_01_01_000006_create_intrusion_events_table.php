<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intrusion_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('threat_rule_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');
            $table->string('severity');
            $table->integer('threat_score')->default(0);
            $table->string('source_ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('method')->nullable();
            $table->text('url')->nullable();
            $table->json('payload')->nullable();
            $table->json('headers')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            $table->index(['type', 'severity']);
            $table->index('source_ip');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intrusion_events');
    }
};
