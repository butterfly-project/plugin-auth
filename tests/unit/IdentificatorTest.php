<?php

namespace Butterfly\Plugin\Auth\Tests;

use Butterfly\Plugin\Auth\Identificator;

class IdentificatorTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateIdentificator()
    {
        $id = 123;
        $parameters = array('foo' => 1, 'bar' => 2);

        $identificator = Identificator::createIdentificator($id, $parameters);

        $this->assertEquals($id, $identificator->getId());
        $this->assertEquals($parameters, $identificator->getParameters());
        $this->assertFalse($identificator->isNullable());
    }

    public function testCreateNullIdentificator()
    {
        $identificator = Identificator::createNullIdentificator();

        $this->assertNull($identificator->getId());
        $this->assertCount(0, $identificator->getParameters());
        $this->assertTrue($identificator->isNullable());
    }
}
