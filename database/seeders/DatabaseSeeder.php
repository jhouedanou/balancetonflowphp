<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Candidate;
use App\Models\LiveStream;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@balancetonflow.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create candidates
        $candidates = [
            [
                'name' => 'MC Flow',
                'description' => 'Rappeur talentueux avec un flow unique et des textes percutants. Originaire de Marseille, il mêle influences méditerranéennes et hip-hop américain.',
                'is_finalist' => true,
            ],
            [
                'name' => 'Lyrical Queen',
                'description' => 'Rappeuse engagée aux textes poétiques et militants. Son style mélange rap conscient et influences soul pour un résultat captivant.',
                'is_finalist' => true,
            ],
            [
                'name' => 'Beat Master',
                'description' => 'Artiste complet qui produit ses propres instrumentales. Son style unique fusionne trap moderne et samples vintage pour un résultat innovant.',
                'is_finalist' => true,
            ],
            [
                'name' => 'Flow Rider',
                'description' => 'Jeune talent prometteur avec une technique impressionnante. Sa capacité à changer de flow et son énergie sur scène en font un candidat redoutable.',
                'is_finalist' => false,
            ],
            [
                'name' => 'Mic Crusher',
                'description' => 'Vétéran de la scène underground, il apporte expérience et authenticité. Ses textes racontent la vie quotidienne avec profondeur et humour.',
                'is_finalist' => false,
            ],
        ];

        foreach ($candidates as $candidateData) {
            Candidate::create($candidateData);
        }

        // Create a sample livestream
        LiveStream::create([
            'title' => 'Demi-finales Balance Ton Flow 2025',
            'description' => 'Les 6 meilleurs candidats s\'affrontent pour décrocher leur place en finale. Votez pour vos 3 favoris !',
            'embed_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'start_time' => now()->addDays(5)->setHour(20)->setMinute(0),
            'end_time' => now()->addDays(5)->setHour(22)->setMinute(30),
            'is_active' => false,
            'phase' => 'semi-final',
        ]);

        // Attach candidates to livestream
        $livestream = LiveStream::first();
        $livestream->candidates()->attach(Candidate::where('is_finalist', true)->pluck('id'));
    }
}
