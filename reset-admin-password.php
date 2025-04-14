<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@balancetonflow.com';
$password = 'hv7dAZCcZbT75ddH';

echo "Recherche de l'utilisateur admin ($email)...\n";

$user = User::where('email', $email)->first();

if (!$user) {
    echo "Utilisateur non trouvé. Création d'un nouvel utilisateur admin...\n";
    
    $user = new User();
    $user->name = 'Admin';
    $user->email = $email;
    $user->is_admin = true;
    $user->email_verified_at = now();
}

$user->password = Hash::make($password);
$user->save();

echo "Mot de passe de l'utilisateur admin réinitialisé avec succès!\n";
echo "Email: $email\n";
echo "Mot de passe: $password\n";
