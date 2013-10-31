<?php

namespace SibSet\Cache\Adapter;

use SibSet\Cache\Exception\EnvironmentException;

class XCacheAdapter implements AdapterInterface
{
    public function __construct()
    {
        if (!function_exists('xcache_get')) {
            throw new EnvironmentException('XCache extension not loaded');
        }
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return xcache_get($key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        xcache_set($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function setExpired($key, $value, $ttl)
    {
        xcache_set($key, $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        xcache_unset($key);
    }

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        return xcache_isset($key);
    }
}
