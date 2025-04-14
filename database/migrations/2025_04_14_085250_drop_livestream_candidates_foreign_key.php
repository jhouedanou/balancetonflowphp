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
        Schema::table('livestream_candidates', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère
            $table->dropForeign(['candidate_id']);
            
            // Renommer la colonne candidate_id en contestant_id
            $table->renameColumn('candidate_id', 'contestant_id');
            
            // Recréer la contrainte de clé étrangère vers la table contestants
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
        Schema::table('livestream_candidates', function (Blueprint $table) {
            // Supprimer la contrainte de clé étrangère vers contestants
            $table->dropForeign(['contestant_id']);
            
            // Renommer la colonne contestant_id en candidate_id
            $table->renameColumn('contestant_id', 'candidate_id');
            
            // Cette partie sera ignorée si la table candidates n'existe plus
            if (Schema::hasTable('candidates')) {
                // Recréer la contrainte de clé étrangère vers la table candidates
                $table->foreign('candidate_id')
                      ->references('id')
                      ->on('candidates')
                      ->onDelete('cascade');
            }
        });
    }
};
