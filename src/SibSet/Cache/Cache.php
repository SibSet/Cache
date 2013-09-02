<?php

namespace SibSet\Cache;

use SibSet\Cache\Adapter\AdapterInterface;
use SibSet\Cache\Exception\CacheException;
use SibSet\Cache\Serializer\SerializerInterface;

class Cache implements CacheInterface
{
    private $_adapter;

    private $_serializer;

    public function __construct(AdapterInterface $adapter, SerializerInterface $serializer)
    {
        $this->_adapter = $adapter;
        $this->_serializer = $serializer;
    }

    public function getItem($key)
    {
        $serialized = $this->_adapter->get($key);
        $value = $serialized ? $this->_serializer->deserialize($serialized) : null;

        return $value;
    }

    public function setItem($key, $value, $ttl = null)
    {
        $serialized = $this->_serializer->serialize($value);

        if ($ttl) {
            $this->_adapter->setExpired($key, $serialized, $ttl);
        } else {
            $this->_adapter->set($key, $serialized);
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
        $this->_adapter->remove($key);
    }

    public function existsItem($key)
    {
        return $this->_adapter->exists($key);
    }
}
