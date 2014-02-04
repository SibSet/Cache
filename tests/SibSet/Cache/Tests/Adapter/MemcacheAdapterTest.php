<?php

namespace SibSet\Cache\Tests\Adapter;

use SibSet\Cache\Adapter\MemcacheAdapter;

class MemcacheAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $memcache;

    /**
     * @var MemcacheAdapter
     */
    public $adapter;

    private function expectsMemcacheCall($method, array $parameters = array(), $return = null)
    {
        $invocation = $this->memcache
            ->expects($this->once())
            ->method($method)
        ;

        if ($parameters) {
            $args = array_map(array($this, 'equalTo'), $parameters);
            call_user_func_array(array($invocation, 'with'), $args);
        }

        if (null !== $return) {
            $invocation->will($this->returnValue($return));
        }
    }

    protected function setUp()
    {
        $this->memcache = $this->getMock('\Memcache', array('get', 'set', 'delete'));
        $this->adapter = new MemcacheAdapter($this->memcache);
    }


    public function testGet()
    {
        $key = 'some:test:key';
        $expected = uniqid();

        $this->expectsMemcacheCall('get', array($key, 0), $expected);

        $actual = $this->adapter->get($key);

        $this->assertEquals($expected, $actual);
    }

    public function testSet()
    {
        $key = 'some:test:key';
        $expected = uniqid(time());

        $this->expectsMemcacheCall('set', array($key, $expected, 0));

        $this->adapter->set($key, $expected);
    }

    public function testSetExpired()
    {
        $key = 'some:test:key';
        $expected = uniqid(time());
        $ttl = 60;

        $this->expectsMemcacheCall('set', array($key, $expected, 0, $ttl));

        $this->adapter->setExpired($key, $expected, $ttl);
    }

    public function testSetExpiredWithExpiredDelay()
    {
        $key = 'some:test:key';
        $expected = uniqid(time());
        $ttl = 60 * 60 * 24 * 35;

        $this->expectsMemcacheCall('set', array($key, $expected, 0, 2592000));

        $this->adapter->setExpired($key, $expected, $ttl);
    }

    public function testRemove()
    {
        $key = 'some:test:key';

        $this->expectsMemcacheCall('delete', array($key));

        $this->adapter->remove($key);
    }

    public function testExists()
    {
        $key = 'some:test:key';

        $this->expectsMemcacheCall('get', array($key, 0), 'I’m gonna break my rusty cage and run!');

        $actual = $this->adapter->exists($key);

        $this->assertTrue($actual);
    }

    public function testCompressed()
    {
        $this->adapter->setCompressed(true);

        $actual = $this->adapter->isCompressed();

        $this->assertTrue($actual);
    }

    public function testCompressionFlagInSet()
    {
        $key = 'some:test:key';
        $expected = uniqid(time());

        $this->expectsMemcacheCall('set', array($key, $expected, \MEMCACHE_COMPRESSED));

        $this->adapter->setCompressed(true);
        $this->adapter->set($key, $expected);
    }

    public function testCompressionFlagInGet()
    {
        $key = 'some:test:key';

        $this->expectsMemcacheCall('get', array($key, \MEMCACHE_COMPRESSED));

        $this->adapter->setCompressed(true);
        $this->adapter->get($key);
    }
}
