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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('type'); 
            $table->integer('port')->nullable();
            $table->string('endpoint')->nullable(); 
            $table->string('keyword')->nullable(); 
            $table->json('credentials')->nullable(); 
            $table->integer('check_interval_minutes')->default(3);
            $table->integer('failure_threshold')->default(2);
            $table->integer('success_threshold')->default(1);
            $table->string('status')->default('pending'); 
            $table->integer('consecutive_failures')->default(0);
            $table->integer('consecutive_successes')->default(0);
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('last_status_change_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('notify_telegram')->default(true);
            $table->boolean('notify_email')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
