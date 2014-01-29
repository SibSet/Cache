<?php

namespace SibSet\Cache\Adapter;

use SibSet\Cache\Exception\EnvironmentException;

class ApcAdapter implements AdapterInterface
{
    public function __construct()
    {
        if (!extension_loaded('apc')) {
            throw new EnvironmentException('APC extension not loaded');
        }
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return apc_fetch($key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        apc_store($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function setExpired($key, $value, $ttl)
    {
        apc_store($key, $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        apc_delete($key);
    }

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        return apc_exists($key);
    }
}
