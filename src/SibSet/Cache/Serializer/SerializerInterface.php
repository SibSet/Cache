<?php

namespace SibSet\Cache\Serializer;

interface SerializerInterface
{
    function serialize($value);

    function deserialize($value);
}
