<?php

namespace SibSet\Cache\Adapter;

class ArrayAdapter implements AdapterInterface
{
    private $_values = array();

    private $_expired = array();

    private function removeExpired($key)
    {
        if (!isset($this->_expired[$key]) || !isset($this->_values[$key])) {
            return;
        }

        list($time, $ttl) = $this->_expired[$key];

        if (time() > ($time + $ttl)) {
            unset($this->_values[$key]);
        }

        if (!isset($this->_values[$key])) {
            unset($this->_expired[$key]);
        }
    }

    public function get($key)
    {
        $this->removeExpired($key);

        return $this->exists($key) ? $this->_values[$key] : null;
    }

    public function set($key, $value)
    {
        $this->_values[$key] = $value;
    }

    public function setExpired($key, $value, $ttl)
    {
        $this->_values[$key] = $value;
        $this->_expired[$key] = array(time(), $ttl);
    }

    public function remove($key)
    {
        $this->removeExpired($key);

        unset($this->_values[$key]);
    }

    public function exists($key)
    {
        $this->removeExpired($key);

        return isset($this->_values[$key]);
    }
}
