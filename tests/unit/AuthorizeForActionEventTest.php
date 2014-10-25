<?php

namespace Butterfly\Plugin\Auth\Tests;

use Butterfly\Plugin\Auth\AuthorizeForActionEvent;
use Butterfly\Plugin\Auth\Identificator;
use Symfony\Component\HttpFoundation\Request;

class AuthorizeForActionEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $request       = $this->getRequest();
        $identificator = $this->getIdentificator();
        $actionCode    = 'action:code';

        $event = new AuthorizeForActionEvent($request, $identificator, $actionCode);

        $this->assertEquals($request, $event->getRequest());
        $this->assertEquals($identificator, $event->getIdentificator());
        $this->assertEquals($actionCode, $event->getActionCode());
    }

    public function testSetActionCode()
    {
        $request       = $this->getRequest();
        $identificator = $this->getIdentificator();

        $event = new AuthorizeForActionEvent($request, $identificator, 'action:code');

        $event->setActionCode('action:code:new');

        $this->assertEquals('action:code:new', $event->getActionCode());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Request
     */
    protected function getRequest()
    {
        return $this->getMock('\Symfony\Component\HttpFoundation\Request');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Identificator
     */
    protected function getIdentificator()
    {
        return $this
            ->getMockBuilder('\Butterfly\Plugin\Auth\Identificator')
            ->disableOriginalConstructor()
            ->getMock();
    }
}
