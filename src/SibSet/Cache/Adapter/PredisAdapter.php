<?php

namespace SibSet\Cache\Adapter;

use Predis\Client;

class PredisAdapter implements AdapterInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->client->get($key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->client->set($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function setExpired($key, $value, $ttl)
    {
        $this->client->setex($key, $ttl, $value);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $this->client->del($key);
    }

    /**
     * @inheritdoc
     */
    public function exists($key)
    {
        return $this->client->exists($key);
    }
}
