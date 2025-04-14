<?php

namespace Database\Seeders;

use App\Models\Contestant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContestantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sauvegarde des données existantes
        $contestants = DB::table('contestants')->get();
        
        // Réinsérer les données
        foreach ($contestants as $contestant) {
            // Éviter de dupliquer les entrées existantes
            if (!Contestant::where('id', $contestant->id)->exists()) {
                // Convertir l'objet stdClass en tableau
                $data = get_object_vars($contestant);
                
                // Conserver l'ID original
                $id = $data['id'];
                unset($data['id']);
                
                // Supprimer les timestamps car ils seront générés automatiquement
                unset($data['created_at']);
                unset($data['updated_at']);
                
                // Insérer avec l'ID original
                DB::table('contestants')->insert(array_merge(['id' => $id], $data));
            }
        }
        
        // Réinitialiser la séquence auto-increment à la valeur max + 1
        $maxId = DB::table('contestants')->max('id') ?: 0;
        DB::statement("ALTER SEQUENCE contestants_id_seq RESTART WITH " . ($maxId + 1));
    }
}
