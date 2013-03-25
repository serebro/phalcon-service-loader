<?php

$config = require('development.php');

$config->merge(new \Phalcon\Config(array(
	'params' => array(
		'debug' => false,
	),
)));

return $config;
