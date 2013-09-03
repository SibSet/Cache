<?php

namespace SibSet\Cache\Adapter;

interface AdapterInterface
{
    public function get($key);

    public function set($key, $value);

    public function setExpired($key, $value, $ttl);

    public function remove($key);

    public function exists($key);
}
