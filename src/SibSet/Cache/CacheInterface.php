<?php

namespace SibSet\Cache;

interface CacheInterface
{
    function getItem($key);

    function setItem($key, $value, $ttl = null);

    function setItemToDate($key, $value, \DateTime $date);

    function removeItem($key);

    function existsItem($key);
}
