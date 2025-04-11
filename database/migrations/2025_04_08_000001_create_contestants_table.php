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
        Schema::create('contestants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->json('social_media_links')->nullable();
            $table->boolean('is_finalist')->default(false);
            $table->string('status')->default('active');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
        
        // Copy data from candidates table if it exists
        if (Schema::hasTable('candidates')) {
            DB::statement('
                INSERT INTO contestants (id, name, bio, profile_photo, is_finalist, user_id, created_at, updated_at)
                SELECT id, name, description, photo, is_finalist, user_id, created_at, updated_at
                FROM candidates
            ');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contestants');
    }
};
