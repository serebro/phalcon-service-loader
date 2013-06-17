#!/usr/bin/env php
<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . '/../');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development');

include_once(APPLICATION_PATH . '/config/constants.php');
include_once(APPLICATION_PATH . '/library/PhalconServiceLoader.php');

/** @var $app \Phalcon\CLI\Console */
$app = \PhalconServiceLoader::createCliApp(APPLICATION_PATH . '/config/console.php');

if ((int)Phalcon\Version::getId() > 1010000) {
	// Phalcon 1.1
	array_shift($argv);
	$task = array_shift($argv);
	$action = array_shift($argv);
	$argv = array_merge(array('task' => $task, 'action' => $action), $argv);
}

$app->handle($argv);
