<?php

defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . '/../app');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development');

include_once(APPLICATION_PATH . '/config/constants.php');
include_once(APPLICATION_PATH . '/library/PhalconServiceLoader.php');

$app = \PhalconServiceLoader::createWebApp(APPLICATION_PATH . '/config/web.php');
echo $app->handle()->getContent();
