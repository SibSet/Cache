<?php

namespace SibSet\Cache;

class ChainCache implements CacheInterface
{
    private $caches = array();

    public function getCaches()
    {
        return $this->caches;
    }

    public function addCache(CacheInterface $cache, $prepend = true)
    {
        if ($this === $cache) {
            throw new \InvalidArgumentException('Recursive detection error, put in the cache itself');
        }

        if ($prepend) {
            array_unshift($this->caches, $cache);
        } else {
            $this->caches[] = $cache;
        }
    }

    public function __construct(array $caches = array())
    {
        array_map(array($this, 'addCache'), $caches);
    }

    /**
     * @inheritdoc
     */
    public function getItem($key)
    {
        /* @var CacheInterface $cache */
        foreach ($this->caches as $cache) {
            if ($cache->existsItem($key)) {
                return $cache->getItem($key);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function setItem($key, $value, $ttl = null)
    {
        /* @var CacheInterface $cache */
        foreach ($this->caches as $cache) {
            $cache->setItem($key, $value, $ttl);
        }
    }

    /**
     * @inheritdoc
     */
    public function setItemToDate($key, $value, \DateTime $date)
    {
        /* @var CacheInterface $cache */
        foreach ($this->caches as $cache) {
            $cache->setItemToDate($key, $value, $date);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeItem($key)
    {
        /* @var CacheInterface $cache */
        foreach ($this->caches as $cache) {
            $cache->removeItem($key);
        }
    }

    /**
     * @inheritdoc
     */
    public function existsItem($key)
    {
        /* @var CacheInterface $cache */
        foreach ($this->caches as $cache) {
            if ($cache->existsItem($key)) {
                return true;
            }
        }

        return false;
    }
}
