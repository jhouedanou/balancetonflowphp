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
        // Add candidate_id to videos table
        Schema::table('videos', function (Blueprint $table) {
            $table->foreignId('candidate_id')->nullable()->after('contestant_id')->constrained()->nullOnDelete();
        });
        
        // Update existing records to set candidate_id = contestant_id
        DB::statement('UPDATE videos SET candidate_id = contestant_id WHERE contestant_id IS NOT NULL');
        
        // Add candidate_id to votes table
        Schema::table('votes', function (Blueprint $table) {
            $table->foreignId('candidate_id')->nullable()->after('contestant_id')->constrained()->nullOnDelete();
        });
        
        // Update existing records to set candidate_id = contestant_id
        DB::statement('UPDATE votes SET candidate_id = contestant_id WHERE contestant_id IS NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
            $table->dropColumn('candidate_id');
        });
        
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);
            $table->dropColumn('candidate_id');
        });
    }
};
