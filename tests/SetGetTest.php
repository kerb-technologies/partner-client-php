<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Kerb\Partner\SetGet;

class SetGetTest extends TestCase
{
    /**
     * @covers Kerb\Partner\SetGet
     */
    public function testSetGet()
    {
        $data = [
            'string' => 'string',
            'int'    => 5,
            'bool'   => true,
            'array'  => ['one' => 1],
            'object' => new \stdClass,
            'null'   => null,
        ];
        $obj = new SetGet();
        foreach ($data as $key => $value) {
            $obj->set($key, $value);
            $this->assertEquals($value, $obj->get($key));
        }
        $this->assertEquals($data, $obj->all());

        $this->assertNull($obj->get('_null_'));
    }

    /**
     * @covers Kerb\Partner\SetGet
     */
    public function testAllData()
    {
        $obj = new SetGet;
        $data = [
            'one' => 'satu',
            'two' => 'dua',
        ];
        foreach ($data as $key => $value) {
            $obj->set($key, $value);
        }

        $this->assertEquals($data, $obj->all(), 'Test get all header value');
    }
}
