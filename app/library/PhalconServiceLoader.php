<?php

// Application configurator
class PhalconServiceLoader {

	/**
	 * @param string $config_filename
	 * @return \Phalcon\Mvc\Application
	 */
	public static function createWebApp($config_filename) {
		return self::createApp('\WebApp', $config_filename);
	}

	/**
	 * @param string $config_filename
	 * @return \Phalcon\CLI\Console
	 */
	public static function createCliApp($config_filename) {
		return self::createApp('\CliApp', $config_filename);
	}

	/**
	 * @param string $app_class_name
	 * @param $config_filename
	 * @return \Phalcon\DI\Injectable
	 */
	public static function createApp($app_class_name, $config_filename) {
		$config = require($config_filename);

		$di = new \Phalcon\DI\FactoryDefault();

		if (isset($config->params)) {
			$di->set('params', $config->params, true);
		}

		/** @var $app \Phalcon\DI\Injectable */
		$app = new $app_class_name($config);
		$app->setDI($di);

		if (empty($app->config->services)) {
			return $app;
		}


		// configure //
		foreach($app->config->services as $name => $params) {
			if (is_callable($params)) {
				$params = function() use($params, $app) { return $params($app);};
				$shared_instance = true;
			} else if ($params instanceof \Phalcon\Config) {
				$shared_instance = !empty($params->shared_instance);
				$params = $params->toArray();
			} else {
				$shared_instance = !empty($params->shared_instance);
			}

			$di->set($name, $params, $shared_instance);
		}

		// force loading
		if ($di->has('loader')) {
			$di->getShared('loader');
		}
		if ($di->has('environment')) {
			$di->get('environment');
		}

		return $app;
	}
}


/**
 * Class CliApp - Console application
 */
class CliApp extends \Phalcon\CLI\Console {

	/** @var \Phalcon\Config */
	public $config;

	public function __construct(\Phalcon\Config $config) {
		$this->config = $config;
	}

	/**
	 * @param Exception $exception
	 */
	public function handleException($exception) {
		// disable error capturing to avoid recursive errors
		restore_error_handler();
		restore_exception_handler();

		$message = $exception->__toString() . "\n";

		if ($this->getDI()->has('logger')) {
			$this->getDI()->get('logger')->log($message, \Phalcon\Logger::ERROR);
		} else {
			error_log($message);
		}
	}

	/**
	 * @param $code
	 * @param $message
	 * @param $file
	 * @param $line
	 */
	public function handleError($code, $message, $file, $line) {
		if ($code & error_reporting()) {
			// disable error capturing to avoid recursive errors
			restore_error_handler();
			restore_exception_handler();

			$log = "$message ($file:$line)\nStack trace:\n";
			$trace = debug_backtrace();
			// skip the first 3 stacks as they do not tell the error position
			if (count($trace) > 3) {
				$trace = array_slice($trace, 3);
			}
			foreach($trace as $i => $t) {
				if (!isset($t['file'])) $t['file'] = 'unknown';
				if (!isset($t['line'])) $t['line'] = 0;
				if (!isset($t['function'])) $t['function'] = 'unknown';
				$log .= "#$i {$t['file']}({$t['line']}): ";
				if (isset($t['object']) && is_object($t['object'])) $log .= get_class($t['object']) . '->';
				$log .= "{$t['function']}()\n";
			}

			if ($this->getDI()->has('logger')) {
				$this->getDI()->get('logger')->log($log, \Phalcon\Logger::ERROR);
			} else {
				error_log($log);
			}
		}
	}
}


/**
 * Class WebApp - Web application
 */
class WebApp extends \Phalcon\Mvc\Application {

	/** @var \Phalcon\Config */
	public $config;


	public function __construct(\Phalcon\Config $config) {
		$this->config = $config;
	}

	/**
	 * @param Exception $exception
	 */
	public function handleException($exception) {
		// disable error capturing to avoid recursive errors
		restore_error_handler();
		restore_exception_handler();

		if (APPLICATION_ENV == 'development') {
			echo '<h1>' . get_class($exception) . '</h1>';
			echo '<p>' . $exception->getMessage() . ' (' . $exception->getFile() . ':' . $exception->getLine() . ')</p>';
			echo '<pre>' . $exception->getTraceAsString() . '</pre>';
		} else {
			echo '<h1>' . get_class($exception) . '</h1>';
			echo '<p>' . $exception->getMessage() . '</p>';
		}

		if ($this->getDI()->has('logger')) {
			$this->getDI()->get('logger')->log($log, \Phalcon\Logger::ERROR);
		} else {
			error_log($log);
		}
	}

	/**
	 * @param $code
	 * @param $message
	 * @param $file
	 * @param $line
	 */
	public function handleError($code, $message, $file, $line) {
		if ($code & error_reporting()) {
			// disable error capturing to avoid recursive errors
			restore_error_handler();
			restore_exception_handler();

			$log = "$message ($file:$line)\nStack trace:\n";
			$trace = debug_backtrace();
			// skip the first 3 stacks as they do not tell the error position
			if (count($trace) > 3) {
				$trace = array_slice($trace, 3);
			}
			foreach($trace as $i => $t) {
				if (!isset($t['file'])) $t['file'] = 'unknown';
				if (!isset($t['line'])) $t['line'] = 0;
				if (!isset($t['function'])) $t['function'] = 'unknown';
				$log .= "#$i {$t['file']}({$t['line']}): ";
				if (isset($t['object']) && is_object($t['object'])) $log .= get_class($t['object']) . '->';
				$log .= "{$t['function']}()\n";
			}
			if (isset($_SERVER['REQUEST_URI'])) $log .= 'REQUEST_URI=' . $_SERVER['REQUEST_URI'];

			if ($this->getDI()->has('logger')) {
				$this->getDI()->get('logger')->log($log, \Phalcon\Logger::ERROR);
			} else {
				error_log($log);
			}

			if (APPLICATION_ENV == 'development') {
				echo '<h1>PHP Error [$code]</h1>';
				echo '<p>$message ($file:$line)</p>';
				echo '<pre>';
				$trace = debug_backtrace();
				// skip the first 3 stacks as they do not tell the error position
				if (count($trace) > 3) {
					$trace = array_slice($trace, 3);
				}
				foreach($trace as $i => $t) {
					if (!isset($t['file'])) {
						$t['file'] = 'unknown';
					}
					if (!isset($t['line'])) {
						$t['line'] = 0;
					}
					if (!isset($t['function'])) {
						$t['function'] = 'unknown';
					}
					echo "#$i {$t['file']}({$t['line']}): ";
					if (isset($t['object']) && is_object($t['object'])) {
						echo get_class($t['object']) . '->';
					}
					echo "{$t['function']}()\n";
				}
				echo '</pre>';
			} else {
				echo "<h1>PHP Error [$code]</h1>\n";
				echo "<p>$message</p>\n";
			}
		}
	}
}
