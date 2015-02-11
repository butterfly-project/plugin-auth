<?php

namespace Butterfly\Plugin\Auth\Tests;

use Butterfly\Adapter\Sf2EventDispatcher\EventDispatcher;
use Butterfly\Application\RequestResponse\Routing\IRouter;
use Butterfly\Application\RequestResponse\Routing\IRouterAware;
use Butterfly\Plugin\Auth\AuthorizationRouter;
use Butterfly\Plugin\Auth\AuthorizeForActionEvent;
use Butterfly\Plugin\Auth\IdentificationService;
use Butterfly\Plugin\Auth\Identificator;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class AuthorizationRouterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAction()
    {
        $routeName      = 'test_route';
        $request        = $this->getRequest();
        $identificator  = $this->getIdentificator();
        $expectedAction = array($routeName, array($request));

        $basicRouter = $this->getBasicRouter();
        $basicRouter
            ->expects($this->once())
            ->method('getAction')
            ->with($request)
            ->will($this->returnValue($expectedAction));

        $identificationService = $this->getIdentificationService();
        $identificationService
            ->expects($this->once())
            ->method('getIdentificator')
            ->with()
            ->will($this->returnValue($identificator));

        $eventDispatcher = $this->getEventDispatcher();
        $eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(AuthorizeForActionEvent::EVENT_NAME, new AuthorizeForActionEvent($request, $identificator, $expectedAction));

        $router = new AuthorizationRouter($basicRouter, $identificationService, $eventDispatcher);

        $result = $router->getAction($request);

        $this->assertEquals($expectedAction, $result);
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|IRouter
     */
    protected function getBasicRouter()
    {
        return $this->getMock('\Butterfly\Application\RequestResponse\Routing\IRouter');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|IdentificationService
     */
    protected function getIdentificationService()
    {
        return $this
            ->getMockBuilder('\Butterfly\Plugin\Auth\IdentificationService')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EventDispatcher
     */
    protected function getEventDispatcher()
    {
        return $this->getMock('\Butterfly\Adapter\Sf2EventDispatcher\EventDispatcher');
    }
}
