<?php

//Time constants
define('TIME_MINUTE', 60);
define('TIME_HOUR', 3600);
define('TIME_DAY', 86400);
define('TIME_WEEK', 604800);
define('TIME_YEAR', 31536000);

// Common parameters
$config = new \Phalcon\Config([
    'admin_email' => 'admin@example.com',
]);

$services = new \Phalcon\Config([
    'config' => $config,
    'loader' => [
        'className' => '\Phalcon\Loader',
        'calls'     => [
            [
                'method'    => 'registerDirs',
                'arguments' => [
                    [
                        'type'  => 'parameter',
                        'value' => [
                            'controllers' => './app/controllers/',
                            'models'      => './app/models/',
                            'library'     => './app/library/',
                            'tasks'       => './app/tasks/',
                        ]
                    ]
                ]
            ],
            ['method' => 'register'],
        ],
    ],
    'logger' => [
        'className' => '\Phalcon\Logger\Adapter\Syslog',
        'arguments' => [
            ['type' => 'parameter', 'value' => null],
        ],
    ],
    'cache'  => [
        'className' => '\Phalcon\Cache\Backend\Memcache',
        'arguments' => [
            [
                'type'      => 'instance',
                'className' => '\Phalcon\Cache\Frontend\Data',
                'arguments' => ['lifetime' => TIME_MINUTE]
            ],
        ],
    ],
]);

return $services;