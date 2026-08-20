<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('threat_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('category');
            $table->string('severity');
            $table->integer('threat_score')->default(0);
            $table->json('patterns')->nullable();
            $table->json('config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_block')->default(false);
            $table->integer('threshold')->default(0);
            $table->string('action')->default('log');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('threat_rules');
    }
};
