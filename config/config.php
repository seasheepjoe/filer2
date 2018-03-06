<?php

$config = [
    'homepage_route' => 'home',
    'db' => [
        'name'     => 'filer2',
        'user'     => 'root',
        'password' => '',
        'host'     => '127.0.0.1',
        'port'     => NULL
    ],
    'routes' => [
        'home'    => 'Main:home',
        'register'=> 'Account:register',
        'login'   => 'Account:login',
        'upload'  => 'Upload:upload',
    ]
];
