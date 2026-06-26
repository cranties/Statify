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
        Schema::create('server_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->float('cpu_usage');
            $table->float('ram_usage');
            $table->float('ram_total')->nullable();
            $table->float('ram_used')->nullable();
            $table->float('disk_usage');
            $table->float('disk_total')->nullable();
            $table->float('disk_used')->nullable();
            $table->string('uptime')->nullable();
            $table->string('health_status')->default('healthy');
            $table->timestamps();

            $table->index('created_at');
            $table->index(['server_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_stats');
    }
};
