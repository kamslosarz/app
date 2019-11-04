<?php

namespace Container\Process;

use Factory\Factory;

abstract class ProcessFactory extends Factory
{
    public static function getInstance($parameters): Process
    {
        self::validate($parameters);

        list($processClass, $constructorParameters) = $parameters;

        return new $processClass($constructorParameters);
    }
}