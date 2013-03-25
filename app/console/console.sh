#!/usr/bin/env php
<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . '/../');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development');

include_once(APPLICATION_PATH . '/config/constants.php');
include_once(APPLICATION_PATH . '/library/PhalconServiceLoader.php');

/** @var $app \Phalcon\CLI\Console */
$app = \PhalconServiceLoader::createCliApp(APPLICATION_PATH . '/config/console.php');
$app->handle($argv);
