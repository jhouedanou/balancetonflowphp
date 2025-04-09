<?php
// Try to include the autoload.php file
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "Autoload.php file loaded successfully!";
} catch (Exception $e) {
    echo "Error loading autoload.php file: " . $e->getMessage();
}
?>
