<?php

$config = require('production.php');

$config->merge(new \Phalcon\Config(array(
	'params' => array(
		'debug' => true,
	),
	'services' => array(
		'environment' => function($app) {
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', 'on');
			set_exception_handler(array($app, 'handleException'));
			set_error_handler(array($app, 'handleError'), error_reporting());
		},
	),
)));

return $config;
