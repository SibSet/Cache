<?php

namespace SibSet\Cache;

interface CacheInterface
{
    public function getItem($key);

    public function setItem($key, $value, $ttl = null);

    public function setItemToDate($key, $value, \DateTime $date);

    public function removeItem($key);

    public function existsItem($key);
}
