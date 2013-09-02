<?php

namespace SibSet\Cache\Tests\Adapter;

use SibSet\Cache\Adapter\ArrayAdapter;

class ArrayAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayAdapter
     */
    public $adapter;

    protected function setUp()
    {
        $this->adapter = new ArrayAdapter();
    }

    public function testGetAndSet()
    {
        $key = 'some:test:key';
        $value = uniqid(time());

        $this->adapter->set($key, $value);
        $actual = $this->adapter->get($key);

        $this->assertEquals($value, $actual);
    }

    public function testExistsValue()
    {
        $key = 'some:test:key';
        $value = uniqid(time());

        $this->adapter->set($key, $value);

        $this->assertTrue($this->adapter->exists($key));
    }

    public function testRemoveValue()
    {
        $key = 'some:test:key';
        $value = uniqid(time());

        $this->adapter->set($key, $value);
        $this->adapter->remove($key);

        $this->assertNull($this->adapter->get($key));
    }

    public function testNotExpiredValue()
    {
        $key = 'some:test:key';
        $value = uniqid(time());
        $ttl = 25;

        $this->adapter->setExpired($key, $value, $ttl);

        $this->assertNotNull($this->adapter->get($key));
    }

    public function testExpiredValue()
    {
        $key = 'some:test:key';
        $value = uniqid(time());
        $ttl = -1;

        $this->adapter->setExpired($key, $value, $ttl);

        $this->assertNull($this->adapter->get($key));
    }
}
