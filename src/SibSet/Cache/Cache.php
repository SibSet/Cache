<?php

namespace SibSet\Cache;

use SibSet\Cache\Adapter\AdapterInterface;
use SibSet\Cache\Exception\CacheException;
use SibSet\Cache\Serializer\SerializerInterface;

class Cache implements CacheInterface
{
    private $adapter;

    private $serializer;

    public function __construct(AdapterInterface $adapter, SerializerInterface $serializer)
    {
        $this->adapter = $adapter;
        $this->serializer = $serializer;
    }

    public function getItem($key)
    {
        $serialized = $this->adapter->get($key);
        $value = $serialized ? $this->serializer->deserialize($serialized) : null;

        return $value;
    }

    public function setItem($key, $value, $ttl = null)
    {
        $serialized = $this->serializer->serialize($value);

        if ($ttl) {
            $this->adapter->setExpired($key, $serialized, $ttl);
        } else {
            $this->adapter->set($key, $serialized);
        }
    }

    public function setItemToDate($key, $value, \DateTime $date)
    {
        $ttl = $date->getTimestamp() - time();

        if ($ttl <= 0) {
            throw new CacheException('Date in the past.');
        }

        $this->setItem($key, $value, $ttl);
    }

    public function removeItem($key)
    {
        $this->adapter->remove($key);
    }

    public function existsItem($key)
    {
        return $this->adapter->exists($key);
    }
}
