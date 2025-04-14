<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contestant;
use App\Models\LiveStream;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Vérifier si des données existent déjà
        $hasUsers = User::count() > 0;
        $hasContestants = Contestant::count() > 0;
        
        if ($hasUsers && $hasContestants) {
            // Utiliser les seeders spécifiques pour sauvegarder et réinsérer les données existantes
            $this->call([
                ContestantSeeder::class,
                LiveStreamSeeder::class,
            ]);
            
            $this->command->info('Données existantes sauvegardées et réinsérées avec succès!');
        } else {
            // Si aucune donnée existante, utiliser les données de démo originales
            // Create or update admin user
            $admin = User::where('email', 'admin@balancetonflow.com')->first();
            
            if (!$admin) {
                $admin = User::create([
                    'name' => 'Admin',
                    'email' => 'admin@balancetonflow.com',
                    'password' => 'admin123', // Sans Hash::make pour éviter le double hachage
                    'email_verified_at' => now(),
                ]);
                
                // Assigner le rôle admin via la table model_has_roles
                DB::table('roles')->insertOrIgnore([
                    'id' => 1,
                    'name' => 'admin',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                DB::table('model_has_roles')->insertOrIgnore([
                    'role_id' => 1,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $admin->id,
                ]);
            } else {
                // Mise à jour directe sans hachage pour éviter le double hachage
                $admin->forceFill([
                    'password' => 'admin123'
                ])->save();
                
                // S'assurer que l'utilisateur a le rôle admin
                if (!$admin->isAdmin()) {
                    DB::table('roles')->insertOrIgnore([
                        'id' => 1,
                        'name' => 'admin',
                        'guard_name' => 'web',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    DB::table('model_has_roles')->insertOrIgnore([
                        'role_id' => 1,
                        'model_type' => 'App\\Models\\User',
                        'model_id' => $admin->id,
                    ]);
                }
            }

            // Create sample contestants
            $contestants = [
                [
                    'name' => 'MC Flow',
                    'description' => 'Rappeur talentueux avec un flow unique et des textes percutants. Originaire de Marseille, il mêle influences méditerranéennes et hip-hop américain.',
                    'is_finalist' => true,
                ],
                // Ajoutez d'autres candidats selon vos besoins
            ];

            foreach ($contestants as $contestantData) {
                Contestant::create($contestantData);
            }

            // Create a sample livestream
            LiveStream::create([
                'title' => 'Demi-finales Balance Ton Flow 2025',
                'description' => 'Les 6 meilleurs candidats s\'affrontent pour décrocher leur place en finale. Votez pour vos 3 favoris !',
                'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'start_time' => now()->addDays(7),
                'end_time' => now()->addDays(7)->addHours(3),
                'is_active' => true,
                'phase' => 'semi-final',
            ]);

            // Attach contestants to livestream
            $livestream = LiveStream::first();
            $livestream->contestants()->attach(Contestant::where('is_finalist', true)->pluck('id'));
        }
    }
}
