<?php

include_once(APPLICATION_PATH . '/config/constants.php');

$config = new \Phalcon\Config(array(
	'params' => array(
		'debug' => false,
		'admin_email' => 'admin@example.com',
	),
	'services' => array(
		'loader' => array(
			'className' => '\Phalcon\Loader',
			'calls' => array(
				array('method' => 'registerDirs', 'arguments' => array(
					array('type' => 'parameter', 'value' => array(
						'controllers' => APPLICATION_PATH . '/controllers/',
						'models'      => APPLICATION_PATH . '/models/',
						'library'     => APPLICATION_PATH . '/library/',
						'tasks'       => APPLICATION_PATH . '/tasks/',
					))
				)),
				array('method' => 'register'),
			),
		),
		'environment' => function($app) {
			error_reporting(0);
			ini_set('log_errors', 1);
			ini_set('display_errors', 0);
			ini_set('display_startup_errors', 0);
			set_exception_handler(array($app, 'handleException'));
			set_error_handler(array($app, 'handleError'), error_reporting());
		},
		'logger' => array(
			'className' => '\Phalcon\Logger\Adapter\Syslog',
			'arguments' => array(
				array('type' => 'parameter', 'value' => null),
			),
			'shared_instance' => true,
		),
		'cache' => array(
			'className' => '\Phalcon\Cache\Backend\Memcache',
			'arguments' => array(
				array('type' => 'instance', 'className' => '\Phalcon\Cache\Frontend\Data', 'arguments' => array('lifetime' => TIME_MINUTE)),
			),
			'shared_instance' => true,
		),
	),
));

return $config;
