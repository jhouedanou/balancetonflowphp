<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePortInRedirects
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
            
            // Si l'URL ne contient pas déjà le port 8889 et commence par http://localhost
            if (strpos($url, 'http://localhost/') === 0 && strpos($url, ':8889') === false) {
                // Remplacer http://localhost/ par http://localhost:8889/
                $newUrl = str_replace('http://localhost/', 'http://localhost:8889/', $url);
                $response->setTargetUrl($newUrl);
            }
        }
        
        return $response;
    }
}
