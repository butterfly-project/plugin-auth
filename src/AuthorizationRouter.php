<?php

namespace Butterfly\Plugin\Auth;

use Butterfly\Adapter\Sf2EventDispatcher\EventDispatcher;
use Butterfly\Application\RequestResponse\Routing\IRouter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Marat Fakhertdinov <marat.fakhertdinov@gmail.com>
 */
class AuthorizationRouter implements IRouter
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
     * @return array|null ($actionName, array $parameters)
     */
    public function getAction(Request $request)
    {
        $action        = $this->router->getAction($request);
        $identificator = $this->identificationService->getIdentificator();

        return $this->authorizeForAction($request, $identificator, $action);
    }

    /**
     * @param Request $request
     * @param Identificator $identificator
     * @param mixed $action
     * @return string
     */
    protected function authorizeForAction(Request $request, Identificator $identificator, $action)
    {
        $event = new AuthorizeForActionEvent($request, $identificator, $action);

        $this->eventDispatcher->dispatch(AuthorizeForActionEvent::EVENT_NAME, $event);

        return $event->getAction();
    }
}
