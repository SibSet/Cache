<?php

namespace SibSet\Cache\Tests;

use SibSet\Cache\Cache;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $serializer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $adapter;

    /**
     * @var Cache
     */
    public $cache;

    protected function setUp()
    {
        $this->adapter = $this->getMock('\SibSet\Cache\Adapter\AdapterInterface');
        $this->serializer = $this->getMock('\SibSet\Cache\Serializer\SerializerInterface');

        $this->cache = new Cache($this->adapter, $this->serializer);
    }

    public function testKeyPrefix()
    {
        $prefix = 'preved:';

        $this->cache->setPrefix($prefix);
        $this->adapter->expects($this->once())
            ->method('get')
            ->with($this->equalTo('preved:medved'));

        $this->cache->getItem('medved');
    }

    public function testGetNotExists()
    {
        $key = 'some:test:key';

        $this->adapter->expects($this->once())
            ->method('get')
            ->with($this->equalTo($key))
            ->will($this->returnValue(null));

        $actual = $this->cache->getItem($key);

        $this->assertNull($actual);
    }

    public function testGet()
    {
        $key = 'some:test:key';
        $expected = uniqid(time());

        $this->adapter->expects($this->once())
            ->method('get')
            ->with($this->equalTo($key))
            ->will($this->returnValue($expected));

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($this->equalTo($expected))
            ->will($this->returnValue($expected));

        $actual = $this->cache->getItem($key);

        $this->assertEquals($expected, $actual);
    }

    public function testSet()
    {
        $key = 'some:test:key';
        $value = uniqid(time());

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($this->equalTo($value))
            ->will($this->returnValue($value));

        $this->adapter->expects($this->once())
            ->method('set')
            ->with($this->equalTo($key), $this->equalTo($value));

        $this->cache->setItem($key, $value);
    }

    public function testSetWithTtl()
    {
        $key = 'some:test:key';
        $value = uniqid(time());
        $ttl = rand(20, 5000);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($this->equalTo($value))
            ->will($this->returnValue($value));

        $this->adapter->expects($this->once())
            ->method('setExpired')
            ->with($this->equalTo($key), $this->equalTo($value), $this->equalTo($ttl));

        $this->cache->setItem($key, $value, $ttl);
    }

    public function testSetToDate()
    {
        $key = 'some:test:key';
        $value = uniqid(time());
        $date = new \DateTime();
        $time =  $date->getTimestamp();
        $date->add(new \DateInterval('PT1H'));

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($this->equalTo($value))
            ->will($this->returnValue($value));

        $this->adapter->expects($this->once())
            ->method('setExpired')
            ->with($this->equalTo($key), $this->equalTo($value), $this->equalTo($date->getTimestamp() - $time));

        $this->cache->setItemToDate($key, $value, $date);
    }

    /**
     * @expectedException \SibSet\Cache\Exception\CacheException
     */
    public function testSetWrongDate()
    {
        $key = 'some:test:key';
        $value = uniqid(time());
        $date = new \DateTime();
        $date->sub(new \DateInterval('PT1H'));

        $this->cache->setItemToDate($key, $value, $date);
    }

    public function testExists()
    {
        $key = 'some:test:key';

        $this->adapter->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($key));

        $this->cache->removeItem($key);
    }

    public function testRemove()
    {
        $key = 'some:test:key';

        $this->adapter->expects($this->once())
            ->method('exists')
            ->with($this->equalTo($key));

        $this->cache->existsItem($key);
    }
}
