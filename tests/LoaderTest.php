<?php

class LoaderTest extends \PHPUnit_Framework_TestCase
{

    public function testConstructor()
    {
        $loader = new \Phalcon\DI\Service\Loader();
        $di = $loader->getDI();
        $this->assertInstanceOf('\Phalcon\DiInterface', $di);

        $di = new Phalcon\DI\FactoryDefault();
        $loader = new \Phalcon\DI\Service\Loader($di);
        $this->assertInstanceOf('\Phalcon\DiInterface', $loader->getDI());
        $this->assertEquals($di, $loader->getDI());
    }

    public function testDefinitions()
    {
        $di = new Phalcon\DI\FactoryDefault();
        $loader = new \Phalcon\DI\Service\Loader($di);
        $definitions = require __DIR__ . '/fixtures/config.php';
        $loader->setDefinitions($definitions);

        $this->assertObjectNotHasAttribute('fn2', $di);
        $di->get('fn2');
        $this->assertObjectHasAttribute('fn2', $di);

        $service = $di->getService('fn2');
        $this->assertTrue($service->isShared());

        $config = $di->get('config');
        $this->assertInstanceOf('\Phalcon\Config', $config);
        $this->assertObjectHasAttribute('admin_email', $config);
        $this->assertEquals('admin@example.com', $config->admin_email);

        $simple = $di->get('simple');
        $this->assertInstanceOf('\stdClass', $simple);
        $service = $di->getService('simple');
        $this->assertTrue($service->isShared());

        $simple2 = $di->get('simple2');
        $this->assertInstanceOf('\stdClass', $simple2);
        $service = $di->getService('simple2');
        $this->assertFalse($service->isShared());

        $simple3 = $di->get('simple3');
        $this->assertInstanceOf('\stdClass', $simple3);

        $simple4 = $di->get('simple4');
        $this->assertInstanceOf('\stdClass', $simple4);

        $simple5 = $di->get('simple5');
        $this->assertInstanceOf('\stdClass', $simple5);
    }

    public function testLoad()
    {
        $di = new Phalcon\DI\FactoryDefault();
        $loader = new \Phalcon\DI\Service\Loader($di);
        $definitions = require __DIR__ . '/fixtures/config.php';
        $loader->setDefinitions($definitions);

        $this->assertObjectNotHasAttribute('fn1', $di);
        $this->assertObjectNotHasAttribute('fn2', $di);

        $loader->load(['fn1', 'fn2']);
        $this->assertObjectHasAttribute('fn1', $di);
        $this->assertObjectHasAttribute('fn2', $di);
    }

    public function testAutoLoad()
    {
        $di = new Phalcon\DI\FactoryDefault();
        $loader = new \Phalcon\DI\Service\Loader($di);
        $definitions = require __DIR__ . '/fixtures/config.php';
        $loader->setDefinitions($definitions, ['fn1']);

        $this->assertObjectHasAttribute('fn1', $di);
        $this->assertObjectNotHasAttribute('fn2', $di);
    }
}
