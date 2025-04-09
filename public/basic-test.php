<?php
// Basic test to check if PHP is working and can connect to database
echo "<h1>Basic Test for Balance Ton Flow Platform</h1>";
echo "<p>PHP version: " . phpversion() . "</p>";

// Test database connection
try {
    $pdo = new PDO(
        'mysql:host=mysql;dbname=balancetonflow',
        'user',
        'password'
    );
    echo "<p style='color:green'>Database connection successful!</p>";
    
    // Display tables in database
    $stmt = $pdo->query("SHOW TABLES");
    echo "<h2>Database Tables:</h2>";
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "<li>{$row[0]}</li>";
    }
    echo "</ul>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Database connection failed: " . $e->getMessage() . "</p>";
}

// Check key directories
echo "<h2>Directory Status:</h2>";
echo "<ul>";
$directories = [
    '/var/www/html/vendor' => is_dir('/var/www/html/vendor') ? 'Exists' : 'Missing',
    '/var/www/html/storage' => is_dir('/var/www/html/storage') ? 'Exists' : 'Missing',
    '/var/www/html/bootstrap/cache' => is_dir('/var/www/html/bootstrap/cache') ? 'Exists' : 'Missing',
    '/var/www/html/storage/framework/views' => is_dir('/var/www/html/storage/framework/views') ? 'Exists' : 'Missing',
    '/var/www/html/storage/framework/cache' => is_dir('/var/www/html/storage/framework/cache') ? 'Exists' : 'Missing',
    '/var/www/html/storage/framework/sessions' => is_dir('/var/www/html/storage/framework/sessions') ? 'Exists' : 'Missing',
];

foreach ($directories as $dir => $status) {
    $color = $status == 'Exists' ? 'green' : 'red';
    echo "<li style='color:{$color}'>{$dir}: {$status}</li>";
}
echo "</ul>";
?>
