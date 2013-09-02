<?php

namespace SibSet\Cache\Adapter;

interface AdapterInterface
{
    function get($key);

    function set($key, $value);

    function setExpired($key, $value, $ttl);

    function remove($key);

    function exists($key);
}
