<?php

namespace SibSet\Cache\Serializer;

interface SerializerInterface
{
    public function serialize($value);

    public function deserialize($value);
}
