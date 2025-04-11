<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add candidate_id to videos table if it doesn't already exist
        if (!Schema::hasColumn('videos', 'candidate_id')) {
            Schema::table('videos', function (Blueprint $table) {
                // If contestant_id doesn't exist, add candidate_id as a new column
                // otherwise, add it after contestant_id
                if (!Schema::hasColumn('videos', 'contestant_id')) {
                    $table->foreignId('candidate_id')->nullable()->constrained()->nullOnDelete();
                } else {
                    $table->foreignId('candidate_id')->nullable()->after('contestant_id')->constrained()->nullOnDelete();
                    // Update existing records to set candidate_id = contestant_id
                    DB::statement('UPDATE videos SET candidate_id = contestant_id WHERE contestant_id IS NOT NULL');
                }
            });
        }
        
        // Add candidate_id to votes table if it doesn't already exist
        if (!Schema::hasColumn('votes', 'candidate_id')) {
            Schema::table('votes', function (Blueprint $table) {
                // If contestant_id doesn't exist, add candidate_id as a new column
                // otherwise, add it after contestant_id
                if (!Schema::hasColumn('votes', 'contestant_id')) {
                    $table->foreignId('candidate_id')->nullable()->constrained()->nullOnDelete();
                } else {
                    $table->foreignId('candidate_id')->nullable()->after('contestant_id')->constrained()->nullOnDelete();
                    // Update existing records to set candidate_id = contestant_id
                    DB::statement('UPDATE votes SET candidate_id = contestant_id WHERE contestant_id IS NOT NULL');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('videos') && Schema::hasColumn('videos', 'candidate_id')) {
            Schema::table('videos', function (Blueprint $table) {
                $table->dropForeign(['candidate_id']);
                $table->dropColumn('candidate_id');
            });
        }
        
        if (Schema::hasTable('votes') && Schema::hasColumn('votes', 'candidate_id')) {
            Schema::table('votes', function (Blueprint $table) {
                $table->dropForeign(['candidate_id']);
                $table->dropColumn('candidate_id');
            });
        }
    }
};
