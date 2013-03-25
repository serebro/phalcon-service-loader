<?php
$config = require(APPLICATION_ENV . '.php');

// WEB settings
$config->merge(new \Phalcon\Config(array(
	'services' => array(
		'session' => array(
			'className' => '\Phalcon\Session\Adapter\Files',
			'calls' => array(array('method' => 'start')),
			'shared_instance' => true,
		),
		'router' => function(){
			return include APPLICATION_PATH . '/config/routes.php';
		},
		'url' => array(
			'className' => '\Phalcon\Mvc\Url',
			'calls' => array(
				array('method' => 'setBaseUri', 'arguments' => array(
					array('type' => 'parameter', 'value' => '/'),
				)),
			),
			'shared_instance' => true,
		),
		'view'   => array(
			'className' => '\Phalcon\Mvc\View',
			'calls' => array(
				array('method' => 'setViewsDir', 'arguments' => array(
					array('type' => 'parameter', 'value' => APPLICATION_PATH . '/views/'),
				)),
			),
			'shared_instance' => true,
		),
	),
)));

return $config;
