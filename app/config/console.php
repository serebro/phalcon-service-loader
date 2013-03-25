<?php

$config = require(APPLICATION_ENV . '.php');

$config->merge(new \Phalcon\Config(array(
	'services' => array(
		'environment' => function($app) {
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', 'on');
			set_exception_handler(array($app, 'handleException'));
			set_error_handler(array($app, 'handleError'), error_reporting());
			set_time_limit(0);
		},
		'router' => array(
			'className' => '\Phalcon\CLI\Router',
		),
		'logger' => array(
			'className' => '\Phalcon\Logger\Adapter\File',
			'arguments' => array(
				array('type' => 'parameter', 'value' => APPLICATION_PATH . '/../cli.log'),
				//array('type' => 'parameter', 'value' => array('mode' => 'w')), // rewrite log file
			),
		),
		'dispatcher' => array(
			'className' => '\Phalcon\CLI\Dispatcher',
			'calls'     => array(
				array('method' => 'setDefaultTask', 'arguments' => array(
					array('type' => 'parameter', 'value' => 'Help'),
				)),
				array('method' => 'setDefaultAction', 'arguments' => array(
					array('type' => 'parameter', 'value' => 'index'),
				)),
			),
		),
	),
)));

return $config;
