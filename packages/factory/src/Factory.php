<?php

namespace Factory;

abstract class Factory
{
    /**
     * @param $parameters
     * @throws FactoryException
     */
    protected static function validate($parameters): void
    {
        if(!isset($parameters[0]))
        {
            throw new FactoryException('Undefined element class');
        }
        if(!is_string($parameters[0]))
        {
            throw new FactoryException(sprintf('Classname must be a string. Given: \'%s\'', gettype($parameters[0])));
        }
        if(!class_exists($parameters[0]))
        {
            throw new FactoryException(sprintf('Class \'%s\' not exists', $parameters[0]));
        }
    }

    /**
     * @param array $parameters
     * @return mixed
     * @throws FactoryException
     */
    public static function getInstance(array $parameters)
    {
        self::validate($parameters);

        $classname = $parameters[0];
        if(isset($parameters[1]))
        {
            return new $classname(...$parameters[1]);
        }

        return new $classname;
    }
}