<?php

return [
    'connections' => [
        'default' => [
            'hosts' => ['10.0.0.2'], // IP máy chủ AD
            'port' => 389,
            'base_dn' => 'dc=cusc,dc=ctu,dc=vn',
            'username' => 'admin.khuong@cusc.ctu.vn', // Tài khoản quản trị AD
            'password' => 'MatKhauQuanTri',
            'use_ssl' => false,
            'use_tls' => false,
        ],
    ],
];