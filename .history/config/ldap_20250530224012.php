<?php

return [
    'connections' => [
        'default' => [
            'hosts' => ['10.0.0.2'], // IP máy chủ AD
            'port' => 389,
            'base_dn' => 'dc=cusc,dc=ctu,dc=vn',
            'username' => 'administrator@cusc.ctu.vn', // Tài khoản quản trị AD
            'password' => 'MatKhauQuanTri',
            'use_ssl' => false,
            'use_tls' => false,
        ],
    ],
];