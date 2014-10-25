<?php

namespace Butterfly\Plugin\Auth;

use Butterfly\Adapter\Sf2EventDispatcher\EventDispatcher;
use Butterfly\Application\RequestResponse\Routing\IRouter;
use Butterfly\Application\RequestResponse\Routing\IRouterAware;
use Butterfly\Component\DI\IInjector;
use Symfony\Component\HttpFoundation\Request;

class AuthorizationRouter implements IRouter, IInjector
{
    /**
     * @var IRouter
     */
    protected $router;

    /**
     * @var IdentificationService
     */
    protected $identificationService;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param IRouter $router
     * @param IdentificationService $identificationService
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(
        IRouter $router,
        IdentificationService $identificationService,
        EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher       = $eventDispatcher;
        $this->identificationService = $identificationService;
        $this->router                = $router;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getActionCode(Request $request)
    {
        $actionCode    = $this->router->getActionCode($request);
        $identificator = $this->identificationService->getIdentificator();

        return $this->authorizeForAction($request, $identificator, $actionCode);
    }

    /**
     * @param Request $request
     * @param Identificator $identificator
     * @param string $actionCode
     * @return string
     */
    protected function authorizeForAction(Request $request, Identificator $identificator, $actionCode)
    {
        $event = new AuthorizeForActionEvent($request, $identificator, $actionCode);
        $this->eventDispatcher->dispatch(AuthorizeForActionEvent::EVENT_NAME, $event);

        return $event->getActionCode();
    }

    /**
     * @param string $route
     * @param array  $parameters
     * @param bool   $isAbsolute
     * @return string The generated URL
     */
    public function generateUrl($route, array $parameters = array(), $isAbsolute = true)
    {
        return $this->router->generateUrl($route, $parameters, $isAbsolute);
    }

    /**
     * @param Object $object
     */
    public function inject($object)
    {
        if ($object instanceof IRouterAware) {
            $object->setRouter($this);
        }
    }
}
