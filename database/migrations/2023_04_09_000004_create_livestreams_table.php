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
        Schema::create('livestreams', function (Blueprint $table) {
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

        // Create pivot table for livestream_candidates
        Schema::create('livestream_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livestream_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Ensure each candidate is only added once per livestream
            $table->unique(['livestream_id', 'candidate_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestream_candidates');
        Schema::dropIfExists('livestreams');
    }
};
