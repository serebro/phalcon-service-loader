<?php

defined('APP_PATH') || define('APP_PATH', dirname(__FILE__) . '/../app');
defined('WEB_PATH') || define('WEB_PATH', dirname(__FILE__));
defined('ENV') || define('ENV', getenv('ENV') ? getenv('ENV') : 'development');

try {

    include __DIR__ . '../vendor/autoload.php';

    /**
     * Read the configuration
     */
    $config = APP_PATH . '/config/' . ENV . '.php';


    /**
     * Load defined services
     */
    $di = new Phalcon\DI\FactoryDefault();
    $serviceLoader = new \Phalcon\DI\Service\Loader($di);
    $serviceLoader->setDefinitions($config, ['loader', 'env']);

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();
} catch(\Exception $e) {
    echo $e->getMessage();
}
