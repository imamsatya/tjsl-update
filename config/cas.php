<?php

return [
    'hostname'              => env('CAS_HOSTNAME', ''),
    'context'               => env('CAS_CONTEXT', '/sso'),
    'port'                  => env('CAS_PORT', 443),
    'real_hosts'            => env('CAS_REAL_HOSTS', ''),
    'logout_redirect_url'   => env('CAS_LOGOUT_REDIRECT_URL', ''),
    'cert'                  => env('CAS_CERT', base_path('cacert.pem')),
    'verbose'               => env('CAS_VERBOSE', false),
];