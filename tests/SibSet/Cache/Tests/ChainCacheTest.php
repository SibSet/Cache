<?php

namespace SibSet\Cache\Tests;

use SibSet\Cache\ChainCache;

class ChainCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $fastCache;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $slowCache;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $otherCache;

    /**
     * @var ChainCache
     */
    public $cache;

    protected function setUp()
    {
        $this->fastCache = $this->getMock('SibSet\Cache\CacheInterface');
        $this->slowCache = $this->getMock('SibSet\Cache\CacheInterface');

        $this->otherCache = $this->getMock('SibSet\Cache\CacheInterface');

        $this->cache = new ChainCache(array(
            $this->slowCache,
            $this->fastCache
        ));
    }

    public function testAddCacheAppend()
    {
        $this->cache->addCache($this->otherCache, false);
        $caches = $this->cache->getCaches();

        $this->assertArrayHasKey(2, $caches);
        $this->assertEquals($this->otherCache, $caches[2]);
    }

    public function testAddCachePrepend()
    {
        $this->cache->addCache($this->otherCache, true);
        $caches = $this->cache->getCaches();

        $this->assertEquals($this->otherCache, $caches[0]);
    }

    public function testGetItem()
    {
        $key = 'test-key';
        $value = uniqid();

        $this->fastCache
            ->expects($this->once())
            ->method('existsItem')
            ->with($this->equalTo($key))
            ->will($this->returnValue(false))
        ;

        $this->slowCache
            ->expects($this->once())
            ->method('existsItem')
            ->with($this->equalTo($key))
            ->will($this->returnValue(true))
        ;
        $this->slowCache
            ->expects($this->once())
            ->method('getItem')
            ->with($this->equalTo($key))
            ->will($this->returnValue($value))
        ;

        $actual = $this->cache->getItem($key);

        $this->assertEquals($value, $actual);
    }

    public function testSetItem()
    {
        $key = 'test-key';
        $value = uniqid();
        $ttl = 3600;

        $this->fastCache
            ->expects($this->once())
            ->method('setItem')
            ->with($this->equalTo($key), $this->equalTo($value), $this->equalTo($ttl))
        ;

        $this->slowCache
            ->expects($this->once())
            ->method('setItem')
            ->with($this->equalTo($key), $this->equalTo($value), $this->equalTo($ttl))
        ;

        $this->cache->setItem($key, $value, $ttl);
    }

    public function testSetItemToDate()
    {
        $key = 'test-key';
        $value = uniqid();
        $date = new \DateTime();

        $this->fastCache
            ->expects($this->once())
            ->method('setItem')
            ->with($this->equalTo($key), $this->equalTo($value), $this->equalTo($date))
        ;

        $this->slowCache
            ->expects($this->once())
            ->method('setItem')
            ->with($this->equalTo($key), $this->equalTo($value), $this->equalTo($date))
        ;

        $this->cache->setItem($key, $value, $date);
    }

    public function testRemoveItem()
    {
        $key = 'test-key';

        $this->fastCache
            ->expects($this->once())
            ->method('removeItem')
            ->with($this->equalTo($key))
        ;

        $this->slowCache
            ->expects($this->once())
            ->method('removeItem')
            ->with($this->equalTo($key))
        ;

        $this->cache->removeItem($key);
    }

    public function testExistsItem()
    {
        $key = 'test-key';

        $this->fastCache
            ->expects($this->once())
            ->method('existsItem')
            ->with($this->equalTo($key))
            ->will($this->returnValue(false))
        ;

        $this->slowCache
            ->expects($this->once())
            ->method('existsItem')
            ->with($this->equalTo($key))
            ->will($this->returnValue(true))
        ;

        $actual = $this->cache->existsItem($key);

        $this->assertEquals(true, $actual);
    }
}
