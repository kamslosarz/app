<?php


namespace Console\Command;

abstract class CommandFactory
{
    public static function getInstance(string $className, array $parameters): Command
    {
        return new $className($parameters);
    }
}