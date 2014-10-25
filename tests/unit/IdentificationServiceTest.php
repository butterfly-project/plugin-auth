<?php

namespace Butterfly\Plugin\Auth\Tests;

use Butterfly\Plugin\Auth\IdentificationService;
use Butterfly\Plugin\Auth\Identificator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IdentificationServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testSetIdentificator()
    {
        $identificator = Identificator::createIdentificator(123);

        $session = $this->getSession();
        $session
            ->expects($this->once())
            ->method('set')
            ->with('id', $identificator);

        $identificatorService = new IdentificationService($session, 'id');

        $identificatorService->setIdentificator($identificator);
    }

    public function testGetIdentificator()
    {
        $identificator = Identificator::createIdentificator(123);

        $session = $this->getSession();
        $session
            ->expects($this->once())
            ->method('get')
            ->withAnyParameters()
            ->will($this->returnValue($identificator));

        $identificatorService = new IdentificationService($session, 'id');

        $this->assertInstanceOf('\Butterfly\Plugin\Auth\Identificator', $identificatorService->getIdentificator());
    }

    public function testRemoveIdentificator()
    {
        $session = $this->getSession();
        $session
            ->expects($this->once())
            ->method('invalidate')
            ->with();

        $identificatorService = new IdentificationService($session, 'id');

        $identificatorService->removeIdentificator();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|SessionInterface
     */
    protected function getSession()
    {
        return $this->getMock('\Symfony\Component\HttpFoundation\Session\SessionInterface');
    }
}
