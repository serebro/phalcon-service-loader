<?php

$router = new Phalcon\Mvc\Router();
$router->removeExtraSlashes(true);

$router->add('/:controller/:action', array(
	'controller' => 1,
	'action'     => 2,
));

$router->add('/:controller', array(
    'controller' => 1,
	'action' => 'index',
));

return $router;
