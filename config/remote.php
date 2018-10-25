<?php
return [
    'default' => 'test',
    'connections' => [
        'production' => [
            'host'      => '111.111.111.111:22',
            'username'  => 'prod',
            'password'  => '', // no password
            'key'       => 'path/to/private.key',
            'keytext'   => '',
            'keyphrase' => '',
            'agent'     => '',
            'timeout'   => 10,
        ],
        'staging' => [
            'host'      => '127.0.0.1',
            'username'  => 'landaovm',
            'password'  => 'dieula',
            'timeout'   => 100,
        ],
        'test' => [
            'host'      => '127.0.0.1',
            'username'  => 'landaovm',
            'password'  => 'dieula',
            'timeout'   => 100,
        ],
    ],
    'groups' => [
        'web' => ['production'],
    ],
];