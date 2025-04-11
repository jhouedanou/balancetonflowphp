<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get or create the admin user
        $user = User::where('email', 'admin@balancetonflow.com')->first();
        
        if (!$user) {
            return; // Skip if user doesn't exist
        }
        
        // Check if admin role exists
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        
        if (!$adminRole) {
            // Create admin role
            $adminRoleId = DB::table('roles')->insertGetId([
                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $adminRoleId = $adminRole->id;
        }
        
        // Check if user already has role
        $hasRole = DB::table('model_has_roles')
            ->where('role_id', $adminRoleId)
            ->where('model_id', $user->id)
            ->where('model_type', 'App\\Models\\User')
            ->exists();
            
        if (!$hasRole) {
            // Assign role to user
            DB::table('model_has_roles')->insert([
                'role_id' => $adminRoleId,
                'model_id' => $user->id,
                'model_type' => 'App\\Models\\User'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get the user
        $user = User::where('email', 'admin@balancetonflow.com')->first();
        
        if (!$user) {
            return;
        }
        
        // Get the admin role
        $adminRole = DB::table('roles')->where('name', 'admin')->first();
        
        if (!$adminRole) {
            return;
        }
        
        // Remove role from user
        DB::table('model_has_roles')
            ->where('role_id', $adminRole->id)
            ->where('model_id', $user->id)
            ->where('model_type', 'App\\Models\\User')
            ->delete();
    }
};
