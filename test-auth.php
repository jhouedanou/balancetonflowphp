<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

echo "Testing authentication for admin@balancetonflow.com...\n";

// Method 1: Try direct authentication
$credentials = [
    'email' => 'admin@balancetonflow.com',
    'password' => 'admin123',
];

if (Auth::attempt($credentials)) {
    echo "Authentication successful using Auth::attempt()!\n";
    $user = Auth::user();
    echo "User ID: " . $user->id . "\n";
    echo "User name: " . $user->name . "\n";
    echo "Is Admin: " . ($user->isAdmin() ? 'Yes' : 'No') . "\n";
} else {
    echo "Authentication failed using Auth::attempt()\n";
    
    // Check if user exists
    $user = User::where('email', 'admin@balancetonflow.com')->first();
    if ($user) {
        echo "User exists in the database with ID: " . $user->id . "\n";
        
        // Try to manually check password
        if (password_verify('admin123', $user->password)) {
            echo "Password is correct when checked manually!\n";
        } else {
            echo "Password is incorrect when checked manually.\n";
            echo "Stored password hash: " . $user->password . "\n";
            
            // Update the password directly
            $user->password = bcrypt('admin123');
            $user->save();
            echo "Password has been updated to 'admin123' with bcrypt().\n";
            
            // Check again
            $user = User::where('email', 'admin@balancetonflow.com')->first();
            if (password_verify('admin123', $user->password)) {
                echo "Password is now correct when checked manually!\n";
            }
        }
    } else {
        echo "User does not exist in the database!\n";
    }
}
