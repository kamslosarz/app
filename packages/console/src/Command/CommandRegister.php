<?php

namespace Console\Command;

class CommandRegister
{
    protected array $commands = [];
    protected array $aliases = [];

    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    public function register(): void
    {
        foreach($this->commands as $classname)
        {
            $this->aliases[$classname::getAlias()] = $classname;
        }
    }

    public function hasAlias(string $name): bool
    {
        return isset($this->aliases[$name]);
    }

    public function getByAlias(string $alias): string
    {
        return $this->aliases[$alias];
    }
}