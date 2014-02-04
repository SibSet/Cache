<?php

namespace SibSet\Cache\Adapter;

class MemcacheAdapter implements AdapterInterface
{
    /**
     * @var \Memcache
     */
    private $memcache;

    /**
     * @var
     */
    private $compressed = false;

    private function getCompressedFlag()
    {
        return $this->isCompressed() ? \MEMCACHE_COMPRESSED : 0;
    }

    public function setCompressed($value)
    {
        $this->compressed = (bool) $value;
    }

    public function isCompressed()
    {
        return $this->compressed;
    }

    public function __construct(\Memcache $memcache)
    {
        $this->memcache = $memcache;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->memcache->get($key, $this->getCompressedFlag());
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->memcache->set($key, $value, $this->getCompressedFlag());
    }

    /**
     * @inheritdoc
     */
    public function setExpired($key, $value, $ttl)
    {
        if ($ttl > 2592000) {
            $ttl = 2592000;
        }

        $this->memcache->set($key, $value, $this->getCompressedFlag(), $ttl);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $this->memcache->delete($key);
    }

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        return $this->get($key) !== false;
    }
}
