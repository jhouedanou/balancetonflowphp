<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Check if user exists first
$user = User::where('email', 'admin@balancetonflow.com')->first();

if (!$user) {
    // Create admin user
    $user = new User();
    $user->name = 'Admin';
    $user->email = 'admin@balancetonflow.com';
    $user->password = Hash::make('hv7dAZCcZbT75ddH');
    $user->save();
    
    echo "Admin user created successfully!\n";
} else {
    // Update admin password
    $user->password = Hash::make('hv7dAZCcZbT75ddH');
    $user->save();
    
    echo "Admin user updated successfully!\n";
}

// Now check if the admin role exists
$adminRole = DB::table('roles')->where('name', 'admin')->first();
if (!$adminRole) {
    // Create admin role
    $adminRoleId = DB::table('roles')->insertGetId([
        'name' => 'admin',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "Admin role created successfully!\n";
} else {
    $adminRoleId = $adminRole->id;
    echo "Admin role already exists.\n";
}

// Assign role to user
$hasRole = DB::table('model_has_roles')
    ->where('role_id', $adminRoleId)
    ->where('model_id', $user->id)
    ->where('model_type', 'App\\Models\\User')
    ->exists();

if (!$hasRole) {
    DB::table('model_has_roles')->insert([
        'role_id' => $adminRoleId,
        'model_id' => $user->id,
        'model_type' => 'App\\Models\\User'
    ]);
    echo "Admin role assigned to user successfully!\n";
} else {
    echo "User already has admin role.\n";
}

echo "Admin credentials:\n";
echo "Email: admin@balancetonflow.com\n";
echo "Password: hv7dAZCcZbT75ddH\n";
