<?php

namespace SibSet\Cache\Serializer;

class DefaultSerializer implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize($value)
    {
        return serialize($value);
    }

    /**
     * @inheritdoc
     */
    public function deserialize($value)
    {
        return unserialize($value);
    }
}
