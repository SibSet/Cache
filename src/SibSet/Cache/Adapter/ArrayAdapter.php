<?php

namespace SibSet\Cache\Adapter;

class ArrayAdapter implements AdapterInterface
{
    private $values = array();

    private $expired = array();

    private function removeExpired($key)
    {
        if (!isset($this->expired[$key]) || !isset($this->values[$key])) {
            return;
        }

        list($time, $ttl) = $this->expired[$key];

        if (time() > ($time + $ttl)) {
            unset($this->values[$key]);
        }

        if (!isset($this->values[$key])) {
            unset($this->expired[$key]);
        }
    }

    public function get($key)
    {
        $this->removeExpired($key);

        return $this->exists($key) ? $this->values[$key] : null;
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function setExpired($key, $value, $ttl)
    {
        $this->values[$key] = $value;
        $this->expired[$key] = array(time(), $ttl);
    }

    public function remove($key)
    {
        $this->removeExpired($key);

        unset($this->values[$key]);
    }

    public function exists($key)
    {
        $this->removeExpired($key);

        return isset($this->values[$key]);
    }
}
