<?php

namespace SibSet\Cache;

use SibSet\Cache\Adapter\AdapterInterface;
use SibSet\Cache\Exception\CacheException;
use SibSet\Cache\Serializer\SerializerInterface;

class Cache implements CacheInterface
{
    private $adapter;

    private $serializer;

    private $prefix = '';

    /**
     * @return mixed
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = (string) $prefix;
    }

    private function calculateStoreKey($rawKey)
    {
        return $this->prefix . $rawKey;
    }

    public function __construct(AdapterInterface $adapter, SerializerInterface $serializer)
    {
        $this->adapter = $adapter;
        $this->serializer = $serializer;
    }

    public function getItem($key)
    {
        $storeKey = $this->calculateStoreKey($key);

        $serialized = $this->adapter->get($storeKey);
        $value = $serialized ? $this->serializer->deserialize($serialized) : null;

        return $value;
    }

    public function setItem($key, $value, $ttl = null)
    {
        $storeKey = $this->calculateStoreKey($key);
        $serialized = $this->serializer->serialize($value);

        if ($ttl) {
            $this->adapter->setExpired($storeKey, $serialized, $ttl);
        } else {
            $this->adapter->set($storeKey, $serialized);
        }
    }

    public function setItemToDate($key, $value, \DateTime $date)
    {
        $ttl = $date->getTimestamp() - time();

        if ($ttl <= 0) {
            throw new CacheException('Date in the past.');
        }

        $storeKey = $this->calculateStoreKey($key);

        $this->setItem($storeKey, $value, $ttl);
    }

    public function removeItem($key)
    {
        $storeKey = $this->calculateStoreKey($key);
        $this->adapter->remove($storeKey);
    }

    public function existsItem($key)
    {
        $storeKey = $this->calculateStoreKey($key);
        return $this->adapter->exists($storeKey);
    }
}
