<?php

namespace Phalcon\DI\Service;

use Phalcon\Config;
use Phalcon\DI\FactoryDefault;
use Phalcon\DI\Injectable;
use Phalcon\DiInterface;

class Loader extends Injectable
{

    public function __construct(DiInterface $di = null)
    {
        if (!$di) {
            $di = new FactoryDefault();
        }

        $this->setDI($di);
    }

    /**
     * @param array|Config $services
     * @param array        $autoLoad - ['loader', 'logger']
     * @return DiInterface
     */
    public function setDefinitions($services, array $autoLoad = [])
    {
        if (!is_array($services) && !$services instanceof Config) {
            throw new \InvalidArgumentException('Parameter "services" must be an array');
        }

        if (!count($services)) {
            return;
        }

        $di = $this->getDI();
        foreach ($services as $name => $params) {
            if (!is_string($params) && is_callable($params, true)) {
                $shared = true;
                $params = function () use ($params, $di) {
                    return $params($di);
                };
            } else {
                if ($params instanceof Config || is_array($params)) {
                    $shared = !(isset($params['shared']) && !$params['shared']);
                }
                if ($params instanceof Config && !empty($params['className'])) {
                    $params = $params->toArray();
                }
            }

            $di->set($name, $params, $shared);
        }

        if (count($autoLoad)) {
            $this->load($autoLoad);
        }
    }

    public function load(array $names)
    {
        $di = $this->getDI();
        foreach ($names as $name) {
            if ($di->has($name)) {
                $di->get($name);
            }
        }
    }
}
