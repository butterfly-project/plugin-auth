<?php

namespace Butterfly\Plugin\Auth;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class AuthorizeForActionEvent extends Event
{
    const EVENT_NAME = 'routing.authorize';

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Identificator
     */
    protected $identificator;

    /**
     * @var string
     */
    protected $actionCode;

    /**
     * @param Request $request
     * @param Identificator $identificator
     * @param string $actionCode
     */
    public function __construct(Request $request, Identificator $identificator, $actionCode)
    {
        $this->request       = $request;
        $this->identificator = $identificator;
        $this->actionCode    = $actionCode;
    }

    /**
     * @return Identificator
     */
    public function getIdentificator()
    {
        return $this->identificator;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getActionCode()
    {
        return $this->actionCode;
    }

    /**
     * @param string $actionCode
     */
    public function setActionCode($actionCode)
    {
        $this->actionCode = $actionCode;
    }
}
