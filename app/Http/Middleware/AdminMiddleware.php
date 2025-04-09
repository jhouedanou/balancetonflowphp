<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user has admin role instead of is_admin flag
        if (Auth::check()) {
            $user = Auth::user();
            $hasAdminRole = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $user->id)
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->where('roles.name', 'admin')
                ->exists();
            
            if ($hasAdminRole) {
                return $next($request);
            }
        }
        
        // Use url() helper with the named route to preserve port number
        return redirect()->to(url(route('home', [], false)))->with('error', 'Unauthorized access');
    }
}
