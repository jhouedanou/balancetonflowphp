<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Check if admin exists
$admin = User::where('email', 'admin@balancetonflow.com')->first();

if (!$admin) {
    // Create admin user
    $admin = new User();
    $admin->name = 'Admin';
    $admin->email = 'admin@balancetonflow.com';
    $admin->password = Hash::make('hv7dAZCcZbT75ddH');
    $admin->is_admin = true;
    $admin->save();

    echo "Admin user created successfully!\n";
} else {
    // Ensure admin has correct password and is_admin flag
    $admin->password = Hash::make('hv7dAZCcZbT75ddH');
    $admin->is_admin = true;
    $admin->save();

    echo "Admin user updated successfully!\n";
}

echo "Admin credentials:\n";
echo "Email: admin@balancetonflow.com\n";
echo "Password: hv7dAZCcZbT75ddH\n";
