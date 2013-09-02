<?php

namespace SibSet\Cache\Adapter;

use Predis\Client;

class PredisAdapter implements AdapterInterface
{
    private $_client;

    public function __construct(Client $client)
    {
        $this->_client = $client;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->_client->get($key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->_client->set($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function setExpired($key, $value, $ttl)
    {
        $this->_client->setex($key, $ttl, $value);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $this->_client->del($key);
    }

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        return $this->_client->exists($key);
    }
}
