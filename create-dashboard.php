<?php
/**
 * Script unifié pour remplacer Filament par un tableau de bord simple
 * Tous les correctifs antérieurs sont fusionnés ici
 */

require __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement depuis .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialiser l'application Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

// Traiter la requête
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Rediriger vers le tableau de bord approprié
$user = auth()->user();

if (!$user) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header('Location: /login');
    exit;
}

if ($user->is_admin) {
    // Rediriger vers le tableau de bord admin
    header('Location: /admin');
} elseif ($user->candidate) {
    // Rediriger vers le tableau de bord candidat
    header('Location: /dashboard');
} else {
    // Rediriger vers la page d'accueil pour les utilisateurs normaux
    header('Location: /');
}

// Terminer la requête
$kernel->terminate($request, $response);
