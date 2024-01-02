<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use phpCAS;

class CasAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!phpCAS::isInitialized()){
            phpCAS::setDebug(storage_path('logs/casdebug.log')); // Enable debugging
            phpCAS::setVerbose(config('cas.verbose')); // Enable verbose error messages. Disable in production!
            phpCAS::client(CAS_VERSION_2_0, config('cas.hostname'), (int) config('cas.port'), config('cas.context'));

            // For quick testing you can disable SSL validation of the CAS server.
            // THIS SETTING IS NOT RECOMMENDED FOR PRODUCTION.
            // VALIDATING THE CAS SERVER IS CRUCIAL TO THE SECURITY OF THE CAS PROTOCOL!
            //phpCAS::setNoCasServerValidation();
            phpCAS::setCasServerCACert(config('cas.cert'));
        }

        if(!phpCAS::isAuthenticated()){
            phpCAS::forceAuthentication();
        }

        $request->session()->put('cas_user', phpCAS::getUser());
        
        return $next($request);
    }
}
