<?php

/** @var \Phalcon\Config $services */
$services = require 'main.php';
$services->merge(new \Phalcon\Config([
    'env' => function ($di) {
        error_reporting(E_ALL | E_STRICT);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
    }
]));

return $services;
