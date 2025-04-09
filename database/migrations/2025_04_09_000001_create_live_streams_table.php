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
        if (!Schema::hasTable('live_streams')) {
            Schema::create('live_streams', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('embed_url');
                $table->string('thumbnail')->nullable();
                $table->timestamp('start_time')->nullable();
                $table->timestamp('end_time')->nullable();
                $table->boolean('is_active')->default(false);
                $table->enum('phase', ['semi-final', 'final'])->default('semi-final');
                $table->timestamps();
            });
        }

        // Create pivot table for live_stream_contestants
        if (!Schema::hasTable('live_stream_contestants')) {
            Schema::create('live_stream_contestants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('live_stream_id')->constrained('live_streams')->onDelete('cascade');
                $table->foreignId('contestant_id')->constrained('contestants')->onDelete('cascade');
                $table->timestamps();
                
                // Ensure each contestant is only added once per live_stream
                $table->unique(['live_stream_id', 'contestant_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_stream_contestants');
        Schema::dropIfExists('live_streams');
    }
};
