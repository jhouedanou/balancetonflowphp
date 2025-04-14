<?php

/**
 * Script d'exportation de données pour Balance Ton Flow
 * 
 * Ce script exporte les données des candidats et livestreams en format SQL
 * pour pouvoir les restaurer facilement lors d'une migration
 */

// Besoin d'accéder aux classes Laravel
require __DIR__.'/../vendor/autoload.php';

// Charger l'application Laravel pour accéder à la base de données
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Date pour le nom de fichier
$date = date('Y-m-d');
$outputFile = __DIR__."/backups/data-backup-{$date}.sql";

// S'assurer que le dossier backups existe
if (!is_dir(__DIR__.'/backups')) {
    mkdir(__DIR__.'/backups', 0755, true);
}

// Ouvrir le fichier de sortie
$output = fopen($outputFile, 'w');

// Fonction pour échapper les valeurs SQL
function sqlEscape($value) {
    if ($value === null) {
        return 'NULL';
    }
    if (is_bool($value)) {
        return $value ? 'TRUE' : 'FALSE';
    }
    if (is_numeric($value)) {
        return $value;
    }
    // Échapper les guillements et les antislashs
    return "'" . str_replace("'", "''", $value) . "'";
}

// Exporter les données des candidats (contestants)
echo "Exportation des candidats...\n";
$contestants = DB::table('contestants')->get();

fwrite($output, "-- Données des candidats (contestants)\n");
fwrite($output, "-- Exportées le {$date}\n\n");

fwrite($output, "-- Désactiver les contraintes de clés étrangères\n");
fwrite($output, "SET FOREIGN_KEY_CHECKS=0;\n\n");

fwrite($output, "-- Vider la table contestants\n");
fwrite($output, "TRUNCATE TABLE contestants;\n\n");

fwrite($output, "-- Insérer les données des candidats\n");
foreach ($contestants as $contestant) {
    $fields = get_object_vars($contestant);
    $columns = implode(', ', array_keys($fields));
    $values = implode(', ', array_map('sqlEscape', array_values($fields)));
    
    fwrite($output, "INSERT INTO contestants ({$columns}) VALUES ({$values});\n");
}

fwrite($output, "\n");

// Exporter les données des livestreams
echo "Exportation des livestreams...\n";
$livestreams = DB::table('live_streams')->get();

fwrite($output, "-- Données des livestreams\n");

fwrite($output, "-- Vider la table live_streams\n");
fwrite($output, "TRUNCATE TABLE live_streams;\n\n");

fwrite($output, "-- Insérer les données des livestreams\n");
foreach ($livestreams as $livestream) {
    $fields = get_object_vars($livestream);
    $columns = implode(', ', array_keys($fields));
    $values = implode(', ', array_map('sqlEscape', array_values($fields)));
    
    fwrite($output, "INSERT INTO live_streams ({$columns}) VALUES ({$values});\n");
}

fwrite($output, "\n");

// Exporter les relations entre livestreams et candidats
echo "Exportation des relations livestream-candidat...\n";
$relations = DB::table('live_stream_contestants')->get();

fwrite($output, "-- Relations entre livestreams et candidats\n");

fwrite($output, "-- Vider la table live_stream_contestants\n");
fwrite($output, "TRUNCATE TABLE live_stream_contestants;\n\n");

fwrite($output, "-- Insérer les relations\n");
foreach ($relations as $relation) {
    $fields = get_object_vars($relation);
    $columns = implode(', ', array_keys($fields));
    $values = implode(', ', array_map('sqlEscape', array_values($fields)));
    
    fwrite($output, "INSERT INTO live_stream_contestants ({$columns}) VALUES ({$values});\n");
}

fwrite($output, "\n");
fwrite($output, "-- Réactiver les contraintes de clés étrangères\n");
fwrite($output, "SET FOREIGN_KEY_CHECKS=1;\n");

// Fermer le fichier
fclose($output);

echo "Exportation terminée! Les données ont été sauvegardées dans {$outputFile}\n";
echo "Pour restaurer ces données, exécutez: php artisan db:seed\n";
