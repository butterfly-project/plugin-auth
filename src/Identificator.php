<?php

namespace Butterfly\Plugin\Auth;

class Identificator
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param mixed $id
     * @param array $parameters
     * @return Identificator
     */
    public static function createIdentificator($id, array $parameters = array())
    {
        return new Identificator($id, $parameters);
    }

    /**
     * @return Identificator
     */
    public static function createNullIdentificator()
    {
        return new Identificator(null, array());
    }

    /**
     * @param mixed $id
     * @param array $parameters
     */
    private function __construct($id, array $parameters)
    {
        $this->id         = $id;
        $this->parameters = $parameters;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return null === $this->id;
    }
}
