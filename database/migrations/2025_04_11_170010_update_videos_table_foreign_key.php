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
        Schema::table('videos', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['candidate_id']);
            
            // Rename the column from candidate_id to contestant_id
            $table->renameColumn('candidate_id', 'contestant_id');
            
            // Add the new foreign key constraint
            $table->foreign('contestant_id')
                  ->references('id')
                  ->on('contestants')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['contestant_id']);
            
            // Rename the column back from contestant_id to candidate_id
            $table->renameColumn('contestant_id', 'candidate_id');
            
            // Re-add the original foreign key constraint
            $table->foreign('candidate_id')
                  ->references('id')
                  ->on('candidates')
                  ->onDelete('cascade');
        });
    }
};
