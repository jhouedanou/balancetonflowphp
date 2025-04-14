<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "Checking for admin user...\n";

// Check if user exists
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
    // Output existing user details
    echo "Admin user found with ID: " . $user->id . "\n";
    
    // Update password
    $user->password = Hash::make('hv7dAZCcZbT75ddH');
    $user->save();
    echo "Password updated to 'hv7dAZCcZbT75ddH'\n";
}

// Check if roles table exists
if (!Schema::hasTable('roles')) {
    echo "Roles table does not exist! Please run the migrations first.\n";
    exit;
}

// Check if the admin role exists
$adminRole = DB::table('roles')->where('name', 'admin')->first();
if (!$adminRole) {
    // Create admin role
    $adminRoleId = DB::table('roles')->insertGetId([
        'name' => 'admin',
        'guard_name' => 'web',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "Admin role created with ID: " . $adminRoleId . "\n";
} else {
    $adminRoleId = $adminRole->id;
    echo "Admin role exists with ID: " . $adminRoleId . "\n";
}

// Check if user has admin role
$hasRole = DB::table('model_has_roles')
    ->where('role_id', $adminRoleId)
    ->where('model_id', $user->id)
    ->where('model_type', 'App\\Models\\User')
    ->exists();

if (!$hasRole) {
    // Assign admin role to user
    DB::table('model_has_roles')->insert([
        'role_id' => $adminRoleId,
        'model_id' => $user->id,
        'model_type' => 'App\\Models\\User'
    ]);
    echo "Admin role assigned to user.\n";
} else {
    echo "User already has admin role.\n";
}

echo "\nVerify these admin credentials in your application:\n";
echo "Email: admin@balancetonflow.com\n";
echo "Password: hv7dAZCcZbT75ddH\n";

// Output how authentication is set up
echo "\nChecking authentication guards configuration:\n";
$guards = config('auth.guards');
print_r($guards);

echo "\nChecking auth providers configuration:\n";
$providers = config('auth.providers');
print_r($providers);

// Check if isAdmin function is working correctly
echo "\nTesting isAdmin() function on user:\n";
$isAdmin = $user->isAdmin();
echo "User->isAdmin() returns: " . ($isAdmin ? "true" : "false") . "\n";

// Verify the user can access the panel
if (method_exists($user, 'canAccessPanel')) {
    echo "User->canAccessPanel() returns: " . ($user->canAccessPanel(new \Filament\Panel) ? "true" : "false") . "\n";
} else {
    echo "canAccessPanel method does not exist\n";
}
