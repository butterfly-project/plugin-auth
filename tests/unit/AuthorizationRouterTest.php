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

class AuthorizationRouterTest extends \PHPUnit_Framework_TestCase
{
    public function testGetActionCode()
    {
        $routeName     = 'test_route';
        $request       = $this->getRequest();
        $identificator = $this->getIdentificator();

        $basicRouter = $this->getBasicRouter();
        $basicRouter
            ->expects($this->once())
            ->method('getActionCode')
            ->with($request)
            ->will($this->returnValue($routeName));

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
            ->with(AuthorizeForActionEvent::EVENT_NAME, new AuthorizeForActionEvent($request, $identificator, $routeName));

        $router = new AuthorizationRouter($basicRouter, $identificationService, $eventDispatcher);

        $result = $router->getActionCode($request);

        $this->assertEquals($routeName, $result);
    }

    public function testInject()
    {
        $basicRouter           = $this->getBasicRouter();
        $identificationService = $this->getIdentificationService();
        $eventDispatcher       = $this->getEventDispatcher();

        $router = new AuthorizationRouter($basicRouter, $identificationService, $eventDispatcher);

        $object = $this->getRouterAware();
        $object
            ->expects($this->once())
            ->method('setRouter')
            ->with($router);

        $router->inject($object);
    }

    public function testInjectIfNotIRouterAware()
    {
        $basicRouter           = $this->getBasicRouter();
        $identificationService = $this->getIdentificationService();
        $eventDispatcher       = $this->getEventDispatcher();

        $router = new AuthorizationRouter($basicRouter, $identificationService, $eventDispatcher);

        $object = $this->getNotIRouterAware();
        $object
            ->expects($this->never())
            ->method('setRouter')
            ->withAnyParameters();

        $router->inject($object);
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
     * @return \PHPUnit_Framework_MockObject_MockObject|IRouterAware
     */
    protected function getRouterAware()
    {
        return $this->getMock('\Butterfly\Application\RequestResponse\Routing\IRouterAware');
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getNotIRouterAware()
    {
        return $this->getMock('\StdClass', array('setRouter'));
    }
}
