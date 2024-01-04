<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $response = $next($request);

        // // Set security headers
        // $response->headers->set('X-CSRF-TOKEN', csrf_token());
        // $response->headers->set('Content-Security-Policy', "default-src 'self';");
        // $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains;');
        // $response->headers->set('X-Content-Type-Options', 'nosniff');
        // $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // $response->headers->set('X-XSS-Protection', '1; mode=block');
        // $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        // return $next($request);


        // $this->removeUnwantedHeaders($this->unwantedHeaderList);

        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Access-Control-Allow-Origin' , '*');
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        if(config('app.env') !== 'local'){
            $response->headers->set('Content-Security-Policy', "upgrade-insecure-requests"); 
        }
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With, Application');
        $response->headers->set("Cache-Control", "no-cache, no-store, must-revalidate");
        $response->headers->set("Pragma", "no-cache");
        $response->headers->set("Expires", 0);

        return $response;        

    }

    private function removeUnwantedHeaders($headerList)
    {
        foreach ($headerList as $header)
            header_remove($header);
    }
}
