Phalcon service loader
======================


## Requirements

* [Phalcon](http://phalconphp.com/)


## Get started

### index.php

```php
  <?php
	defined('APPLICATION_PATH') || 
	define('APPLICATION_PATH', '/app');
	
	defined('APPLICATION_ENV') || 
	define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development');
	
	include_once(APPLICATION_PATH . '/PhalconServiceLoader.php');

	$app = \PhalconServiceLoader::createWebApp(APPLICATION_PATH . '/config.php');
	echo $app->handle()->getContent();
```

###config.php

```php
  <?php
	return new \Phalcon\Config(array(
		'services' => array(
			'loader' => array(
	            // ...
			),
			'logger' => array(
	            // ...
	            'shared_instance' => true,
			),
		),
	));
```
	
## Phalcon services

[See more](http://docs.phalconphp.com/en/latest/reference/di.html#complex-registration)

#####Reserved

* "services" - services definitions
* "shared_instances" - register the service as "always shared"


### "Loader"
```php
	...
	'services' => array(
		'loader' => array(
			'className' => '\Phalcon\Loader',
			'calls' => array(
				array('method' => 'registerDirs', 'arguments' => array(
					array('type' => 'parameter', 'value' => array(
						'controllers' => APPLICATION_PATH . '/controllers/',
						'models'      => APPLICATION_PATH . '/models/',
						'library'     => APPLICATION_PATH . '/library/',
					))
				)),
				array('method' => 'register'),
			),
		),
	),
	...
```

### "Environment" (Production)
```php
	...
	'services' => array(
		'environment' => function(\Phalcon\Mvc\Application $app) {
			error_reporting(0);
			ini_set('log_errors', 1);
			ini_set('display_errors', 0);
			ini_set('display_startup_errors', 0);
			set_exception_handler(array($app, 'handleException'));
			set_error_handler(array($app, 'handleError'), error_reporting());
		},
	),
	...
```

### "Logger"
```php
	...
	'services' => array(
		'logger' => array(
			'className' => '\Phalcon\Logger\Adapter\Syslog',
			'arguments' => array(
				array('type' => 'parameter', 'value' => null),
			),
			'shared_instance' => true,
		),
	),
	...
```

### "Memcache"
```php
	...
	'services' => array(
		'cache' => array(
			'className' => '\Phalcon\Cache\Backend\Memcache',
			'arguments' => array(
				array(
					'type' => 'instance', 
					'className' => '\Phalcon\Cache\Frontend\Data', 
					'arguments' => array('lifetime' => 60)
				),
			),
			'shared_instance' => true,
		),
	),
	...
```

### "Session"
```php
	...
	'services' => array(
		'session' => array(
			'className' => '\Phalcon\Session\Adapter\Files',
			'calls' => array(array('method' => 'start')),
			'shared_instance' => true,
		),
	),
	...
```

### "Url"
```php
	...
	'services' => array(
		'url' => array(
			'className' => '\Phalcon\Mvc\Url',
			'calls' => array(
				array('method' => 'setBaseUri', 'arguments' => array(
					array('type' => 'parameter', 'value' => '/'),
				)),
			),
			'shared_instance' => true,
		),
	),
	...
```

### "View"
```php
	...
	'services' => array(
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
	...
```
