<?php

namespace Butterfly\Plugin\Auth\Tests;

use Butterfly\Plugin\Auth\AuthorizeForActionEvent;
use Butterfly\Plugin\Auth\Identificator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class AuthorizeForActionEventTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $request       = $this->getRequest();
        $identificator = $this->getIdentificator();
        $action        = array('action:code', $request);

        $event = new AuthorizeForActionEvent($request, $identificator, $action);

        $this->assertEquals($request, $event->getRequest());
        $this->assertEquals($identificator, $event->getIdentificator());
        $this->assertEquals($action, $event->getAction());
    }

    public function testSetActionCode()
    {
        $request       = $this->getRequest();
        $identificator = $this->getIdentificator();
        $action        = array('action:code', $request);

        $event = new AuthorizeForActionEvent($request, $identificator, $action);

        $newAction = array('action:code:new', $request);
        $event->setAction($newAction);

        $this->assertEquals($newAction, $event->getAction());
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
