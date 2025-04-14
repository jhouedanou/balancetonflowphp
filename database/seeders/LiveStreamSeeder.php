<?php

namespace Database\Seeders;

use App\Models\LiveStream;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LiveStreamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sauvegarde des données LiveStream existantes
        $liveStreams = DB::table('live_streams')->get();
        
        // Réinsérer les données
        foreach ($liveStreams as $liveStream) {
            // Éviter de dupliquer les entrées existantes
            if (!LiveStream::where('id', $liveStream->id)->exists()) {
                // Convertir l'objet stdClass en tableau
                $data = get_object_vars($liveStream);
                
                // Conserver l'ID original
                $id = $data['id'];
                unset($data['id']);
                
                // Supprimer les timestamps car ils seront générés automatiquement
                unset($data['created_at']);
                unset($data['updated_at']);
                
                // Insérer avec l'ID original
                DB::table('live_streams')->insert(array_merge(['id' => $id], $data));
            }
        }
        
        // Sauvegarde des relations livestream-contestants
        $liveStreamContestants = DB::table('live_stream_contestants')->get();
        
        // Réinsérer les relations
        foreach ($liveStreamContestants as $relation) {
            // Éviter de dupliquer les entrées existantes
            if (!DB::table('live_stream_contestants')
                    ->where('live_stream_id', $relation->live_stream_id)
                    ->where('contestant_id', $relation->contestant_id)
                    ->exists()) {
                
                // Convertir l'objet stdClass en tableau et insérer
                $data = get_object_vars($relation);
                
                // Supprimer les timestamps s'ils existent
                unset($data['created_at']);
                unset($data['updated_at']);
                
                DB::table('live_stream_contestants')->insert($data);
            }
        }
        
        // Réinitialiser la séquence auto-increment à la valeur max + 1
        $maxId = DB::table('live_streams')->max('id') ?: 0;
        // Utilisation de la syntaxe MySQL au lieu de PostgreSQL
        DB::statement("ALTER TABLE live_streams AUTO_INCREMENT = " . ($maxId + 1));
    }
}
