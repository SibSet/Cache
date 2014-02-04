<?php

namespace SibSet\Cache\Tests\Adapter;

use SibSet\Cache\Adapter\PredisAdapter;

class PredisAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $client;

    /**
     * @var PredisAdapter
     */
    public $adapter;

    private function expectsClientCall($method, array $parameters = array())
    {
        $this->client
            ->expects($this->once())
            ->method('__call')
            ->with($this->equalTo($method), $this->equalTo($parameters))
        ;
    }

    protected function setUp()
    {
        $this->client = $this->getMock('\Predis\Client', array('get', 'set', 'setex', 'del', 'exists'));
        $this->adapter = new PredisAdapter($this->client);
    }

    public function testGet()
    {
        $key = 'some:test:key';

        $this->expectsClientCall('get', array($key));

        $this->adapter->get($key);
    }

    public function testSet()
    {
        $key = 'some:test:key';
        $value = uniqid(time());

        $this->expectsClientCall('set', array($key, $value));

        $this->adapter->set($key, $value);
    }

    public function testSetExpired()
    {
        $key = 'some:test:key';
        $value = uniqid(time());
        $ttl = 60;

        $this->expectsClientCall('setex', array($key, $ttl, $value));

        $this->adapter->setExpired($key, $value, $ttl);
    }

    public function testRemove()
    {
        $key = 'some:test:key';

        $this->expectsClientCall('del', array($key));

        $this->adapter->remove($key);
    }

    public function testExists()
    {
        $key = 'some:test:key';

        $this->expectsClientCall('exists', array($key));

        $this->adapter->exists($key);
    }
}
