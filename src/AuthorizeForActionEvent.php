<?php

namespace Butterfly\Plugin\Auth;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
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
    protected $action;

    /**
     * @param Request $request
     * @param Identificator $identificator
     * @param string $action
     */
    public function __construct(Request $request, Identificator $identificator, $action)
    {
        $this->request       = $request;
        $this->identificator = $identificator;
        $this->action        = $action;
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }
}
