Phalcon service loader
======================
Config loader for Phalcon PHP framework 1.3

## Requirements

* [Phalcon](http://phalconphp.com/)


## Get started

### index.php

```php
  <?php
	defined('APP_PATH') || define('APP_PATH', dirname(__FILE__) . '/../app');
	defined('WEB_PATH') || define('WEB_PATH', dirname(__FILE__));
	defined('ENV') || define('ENV', getenv('ENV') ? getenv('ENV') : 'development');
	
	$config = APP_PATH . '/config/' . ENV . '.php';

	//Create a DI
    $di = new Phalcon\DI\FactoryDefault();

	// Service loading
    $serviceLoader = new \Phalcon\DI\Service\Loader($di);
    $serviceLoader->setDefinitions($config, ['loader', 'env']);

	//Handle the request
	$app = new \Phalcon\Mvc\Application($di);
	echo $app->handle()->getContent();
```

###development.php

```php
  <?php
	return new \Phalcon\Config([
		'loader' => [
			// ...
		],
		'logger' => [
			// ...
			'shared' => false,
		],
		'cache' => function($di) {
			// ...
			return $cache;
		}
	]);
```
	
## Phalcon services

[See more "DI - complex registration"](http://docs.phalconphp.com/en/latest/reference/di.html#complex-registration)

#####Reserved

* "shared" - register the service as "always shared". Default: true.


### "Loader"
```php
	'loader' => [
		'className' => '\Phalcon\Loader',
		'calls' => [
			['method' => 'registerDirs', 'arguments' => [
				['type' => 'parameter', 'value' => [
					'controllers' => APP_PATH . '/controllers/',
					'models'      => APP_PATH . '/models/',
					'library'     => APP_PATH . '/library/',
				]]
			]],
			['method' => 'register'],
		],
	],
```

### "Environment" (Production)
```php
	'env' => function($di) {
		error_reporting(0);
		ini_set('log_errors', 1);
		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
	},
```

### "Logger"
```php
	'logger' => [
		'className' => '\Phalcon\Logger\Adapter\Syslog',
		'arguments' => [
			['type' => 'parameter', 'value' => null],
		),
	),
```

### "MySQL"

```php
	'db' => [
		'className' => '\Phalcon\Db\Adapter\Pdo\Mysql',
		'arguments' => [
			['type' => 'parameter', 'value' => [
				'host' => 'localhost',
				'username' => 'root',
				'password' => 'secret',
				'dbname' => 'test_db'
			]],
		],
	],
```

### "Memcache"
```php
	'cache' => [
		'className' => '\Phalcon\Cache\Backend\Memcache',
		'arguments' => [
			[
				'type' => 'instance',
				'className' => '\Phalcon\Cache\Frontend\Data',
				'arguments' => ['lifetime' => 60]
			],
		],
	),
```

### "Session"
```php
	'session' => [
		'className' => '\Phalcon\Session\Adapter\Files',
		'calls' => [
			['method' => 'start']
		],
	),
```

### "Url"
```php
	'url' => [
		'className' => '\Phalcon\Mvc\Url',
		'calls' => [
			['method' => 'setBaseUri', 'arguments' => [
				['type' => 'parameter', 'value' => '/'],
			]],
		],
	],
```

### "View"
```php
	'view'   => [
		'className' => '\Phalcon\Mvc\View',
		'calls' => [
			['method' => 'setViewsDir', 'arguments' => [
				['type' => 'parameter', 'value' => APP_PATH . '/views/'],
			]],
		],
	],
```
