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
        // Assurez-vous que les tables existent
        if (Schema::hasTable('candidates') && Schema::hasTable('contestants')) {
            // Copier les données de candidates vers contestants
            // Convertir les champs correspondants (description -> bio, photo -> profile_photo)
            // Ignorer les enregistrements qui existent déjà (basé sur l'ID)
            DB::statement('
                INSERT INTO contestants (id, name, bio, profile_photo, is_finalist, user_id, created_at, updated_at)
                SELECT c.id, c.name, c.description, c.photo, c.is_finalist, c.user_id, c.created_at, c.updated_at
                FROM candidates c
                LEFT JOIN contestants ct ON c.id = ct.id
                WHERE ct.id IS NULL
            ');

            // Mettre à jour les données existantes pour garantir la cohérence
            $candidates = DB::table('candidates')->get();
            foreach ($candidates as $candidate) {
                DB::table('contestants')
                    ->where('id', $candidate->id)
                    ->update([
                        'name' => $candidate->name,
                        'bio' => $candidate->description,
                        'profile_photo' => $candidate->photo,
                        'is_finalist' => $candidate->is_finalist,
                        'user_id' => $candidate->user_id,
                        'updated_at' => $candidate->updated_at
                    ]);
            }

            // Mettre à jour les références dans la table videos
            if (Schema::hasColumn('videos', 'candidate_id') && !Schema::hasColumn('videos', 'contestant_id')) {
                Schema::table('videos', function (Blueprint $table) {
                    $table->renameColumn('candidate_id', 'contestant_id');
                });
            } else if (Schema::hasColumn('videos', 'candidate_id') && Schema::hasColumn('videos', 'contestant_id')) {
                // Si les deux colonnes existent, copier les valeurs et supprimer l'ancienne colonne
                DB::statement('UPDATE videos SET contestant_id = candidate_id WHERE contestant_id IS NULL');
                Schema::table('videos', function (Blueprint $table) {
                    $table->dropColumn('candidate_id');
                });
            }

            // Mettre à jour les références dans la table votes
            if (Schema::hasColumn('votes', 'candidate_id') && !Schema::hasColumn('votes', 'contestant_id')) {
                Schema::table('votes', function (Blueprint $table) {
                    $table->renameColumn('candidate_id', 'contestant_id');
                });
            } else if (Schema::hasColumn('votes', 'candidate_id') && Schema::hasColumn('votes', 'contestant_id')) {
                // Si les deux colonnes existent, copier les valeurs et supprimer l'ancienne colonne
                DB::statement('UPDATE votes SET contestant_id = candidate_id WHERE contestant_id IS NULL');
                Schema::table('votes', function (Blueprint $table) {
                    $table->dropColumn('candidate_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette migration est destructive et ne peut pas être annulée correctement
    }
};
