<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixFilamentRedirects
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Si la réponse est une redirection
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            $url = $response->getTargetUrl();
            
            // Si l'URL est http://localhost/dashboard, redirigez vers l'admin de Filament
            if ($url === 'http://localhost/dashboard') {
                return redirect()->to('http://localhost:8889/admin');
            }
            
            // Si l'URL commence par http://localhost/ sans le port
            if (strpos($url, 'http://localhost/') === 0 && strpos($url, ':8889') === false) {
                $newUrl = str_replace('http://localhost/', 'http://localhost:8889/', $url);
                return redirect()->to($newUrl);
            }
        }
        
        return $response;
    }
}
