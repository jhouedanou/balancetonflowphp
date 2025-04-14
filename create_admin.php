<?php

// Script à exécuter directement sur le serveur de production
// Utiliser: php create_admin.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// 1. Supprimer l'utilisateur admin existant (si présent)
$admin = User::where('email', 'admin@balancetonflow.com')->first();
if ($admin) {
    echo "Suppression de l'utilisateur admin existant...\n";
    $admin->delete();
}

// 2. Créer un nouvel utilisateur admin
echo "Création d'un nouvel utilisateur admin...\n";
$admin = User::create([
    'name' => 'Admin',
    'email' => 'admin@balancetonflow.com',
    'password' => 'admin123', // Le modèle s'occupera du hachage via le cast
    'email_verified_at' => now(),
]);

// 3. S'assurer que la table roles existe
if (!Schema::hasTable('roles')) {
    echo "Création de la table roles...\n";
    Schema::create('roles', function ($table) {
        $table->id();
        $table->string('name');
        $table->string('guard_name')->default('web');
        $table->timestamps();
    });
}

// 4. S'assurer que la table model_has_roles existe
if (!Schema::hasTable('model_has_roles')) {
    echo "Création de la table model_has_roles...\n";
    Schema::create('model_has_roles', function ($table) {
        $table->unsignedBigInteger('role_id');
        $table->string('model_type');
        $table->unsignedBigInteger('model_id');
        
        $table->primary(['role_id', 'model_id', 'model_type']);
        
        $table->foreign('role_id')
            ->references('id')
            ->on('roles')
            ->onDelete('cascade');
    });
}

// 5. Créer ou trouver le rôle admin
$role = DB::table('roles')->where('name', 'admin')->first();
if (!$role) {
    echo "Création du rôle admin...\n";
    DB::table('roles')->insert([
        'id' => 1,
        'name' => 'admin',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $roleId = 1;
} else {
    $roleId = $role->id;
}

// 6. Assigner le rôle admin à l'utilisateur
$hasRole = DB::table('model_has_roles')
    ->where('role_id', $roleId)
    ->where('model_id', $admin->id)
    ->where('model_type', 'App\\Models\\User')
    ->exists();

if (!$hasRole) {
    echo "Attribution du rôle admin à l'utilisateur...\n";
    DB::table('model_has_roles')->insert([
        'role_id' => $roleId,
        'model_id' => $admin->id,
        'model_type' => 'App\\Models\\User',
    ]);
}

// 7. Vérifier la configuration
$checkAdmin = User::where('email', 'admin@balancetonflow.com')->first();
if ($checkAdmin) {
    echo "✅ Utilisateur admin créé avec succès!\n";
    echo "Email: admin@balancetonflow.com\n";
    echo "Mot de passe: admin123\n";
    
    if ($checkAdmin->isAdmin()) {
        echo "✅ L'utilisateur a bien le rôle admin\n";
    } else {
        echo "❌ ERREUR: L'utilisateur n'a pas le rôle admin\n";
    }
} else {
    echo "❌ ERREUR: Impossible de créer l'utilisateur admin\n";
}

echo "\nTerminé!\n";
