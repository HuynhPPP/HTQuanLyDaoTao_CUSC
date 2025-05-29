<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default LDAP Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the LDAP connections below you wish
    | to use as your default connection for all LDAP operations. Of
    | course you may add as many connections you'd like below.
    |
    */

    'default' => env('LDAP_CONNECTION', 'default'),

    /*
    |--------------------------------------------------------------------------
    | LDAP Connections
    |--------------------------------------------------------------------------
    |
    | Below you may configure each LDAP connection your application requires
    | access to. Be sure to include a valid base DN - otherwise you may
    | not receive any results when performing LDAP search operations.
    |
    */

    'connections' => [

        // 'default' => [
        //     'hosts' => [env('LDAP_HOST', '10.0.0.2')],
        //     'username' => env('LDAP_USERNAME', 'admin.khuong@cusc.edu.vn'),
        //     'password' => env('LDAP_PASSWORD', 'P@ssW@rd2025!'),
        //     'port' => env('LDAP_PORT', 389),
        //     'base_dn' => env('LDAP_BASE_DN', 'DC=cusc,DC=ctu,DC=vn'),
        //     'timeout' => env('LDAP_TIMEOUT', 10),
        //     'use_ssl' => env('LDAP_SSL', false),
        //     'use_tls' => env('LDAP_TLS', false), // Bạn có thể thêm dòng này nếu cần TLS
        // ],
        'default' => [
            'hosts' => ['10.0.0.2'],
            'username' => 'cusc\\admin.khuong',  // hoặc thử admin.khuong@cusc.ctu.vn
            'password' => 'P@ssW@rd2025!',
            'port' => 389,
            'base_dn' => 'DC=cusc,DC=ctu,DC=vn',
            'timeout' => 10,
            'use_ssl' => false,
            'use_tls' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | LDAP Logging
    |--------------------------------------------------------------------------
    |
    | When LDAP logging is enabled, all LDAP search and authentication
    | operations are logged using the default application logging
    | driver. This can assist in debugging issues and more.
    |
    */

    'logging' => [
        'enabled' => env('LDAP_LOGGING', true),
        'channel' => env('LOG_CHANNEL', 'stack'),
        'level' => env('LOG_LEVEL', 'info'),
    ],

    /*
    |--------------------------------------------------------------------------
    | LDAP Cache
    |--------------------------------------------------------------------------
    |
    | LDAP caching enables the ability of caching search results using the
    | query builder. This is great for running expensive operations that
    | may take many seconds to complete, such as a pagination request.
    |
    */

    'cache' => [
        'enabled' => env('LDAP_CACHE', false),
        'driver' => env('CACHE_DRIVER', 'file'),
    ],

];
