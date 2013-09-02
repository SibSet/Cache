<?php

namespace SibSet\Cache\Tests\Serializer;

use SibSet\Cache\Serializer\DefaultSerializer;

class DefaultSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DefaultSerializer
     */
    public $serializer;

    protected function setUp()
    {
        $this->serializer = new DefaultSerializer();
    }

    public function testSerialize()
    {
        $value = uniqid(time());

        $actual = $this->serializer->serialize($value);
        $expected = serialize($value);

        $this->assertEquals($expected, $actual);
    }

    public function testDeserialize()
    {
        $value = serialize(uniqid(time()));
        $expected = unserialize($value);

        $actual = $this->serializer->deserialize($value);

        $this->assertEquals($expected, $actual);
    }
}
