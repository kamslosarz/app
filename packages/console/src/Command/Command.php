<?php

namespace Console\Command;

abstract class Command
{
    protected $parameters = [];

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    abstract public function __invoke(): bool;
}