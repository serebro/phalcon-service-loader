<?php

/** @var \Phalcon\Config $services */
$services = require 'main.php';
$services->merge(new \Phalcon\Config([
    'env' => function ($di) {
        error_reporting(0);
        ini_set('log_errors', 1);
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
    }
]));

return $services;
