<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectStagingTraffic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $stagingUrl = 'alkamelah1.anwaralolmaa.com';
        $productionUrl = 'alkamelah.com';

        // Check if we are on the staging domain
        if ($host === $stagingUrl) {
            
            // 1. Check for bypass cookie
            if ($request->cookie('staging_bypass')) {
                return $next($request);
            }

            // 2. Check for bypass query parameter (one-time activation)
            // Example: ?access_staging=secret123
            if ($request->query('access_staging') === 'secret123') {
                cookie()->queue(cookie()->forever('staging_bypass', true));
                return $next($request);
            }

            // 3. Otherwise, show the staging interstitial page
            return response()->view('errors.staging', [
                'production_url' => "https://{$productionUrl}" . $request->getRequestUri()
            ]);
        }

        return $next($request);
    }
}
