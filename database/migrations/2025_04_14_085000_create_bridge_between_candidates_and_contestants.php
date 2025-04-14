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
        // Assurons-nous que les contestants ont les mêmes IDs que les candidates correspondants
        if (Schema::hasTable('candidates') && Schema::hasTable('contestants')) {
            // Vérifier si la table contestants est vide
            $contestantCount = DB::table('contestants')->count();
            $candidateCount = DB::table('candidates')->count();
            
            if ($contestantCount == 0 && $candidateCount > 0) {
                // Copier les données de candidates vers contestants
                DB::statement('
                    INSERT INTO contestants (id, name, bio, profile_photo, is_finalist, user_id, created_at, updated_at)
                    SELECT id, name, description, photo, is_finalist, user_id, created_at, updated_at
                    FROM candidates
                ');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cette migration ne peut pas être annulée de manière appropriée
    }
};
