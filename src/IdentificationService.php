<?php

namespace Butterfly\Plugin\Auth;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class IdentificationService
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var string
     */
    protected $parameterName;

    /**
     * @param SessionInterface $session
     * @param string $parameterName
     */
    public function __construct(SessionInterface $session, $parameterName)
    {
        $this->session       = $session;
        $this->parameterName = $parameterName;
    }

    /**
     * @param Identificator $identificator
     */
    public function setIdentificator(Identificator $identificator)
    {
        $this->session->set($this->parameterName, $identificator);
    }

    /**
     * @return Identificator
     */
    public function getIdentificator()
    {
        return $this->session->get($this->parameterName, Identificator::createNullIdentificator());
    }

    public function removeIdentificator()
    {
        $this->session->invalidate();
    }
}
