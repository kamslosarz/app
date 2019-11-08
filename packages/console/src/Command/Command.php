<?php

namespace Console\Command;

use Collection\Collection;

abstract class Command
{
    protected Collection $parameters;
    protected ?string $results = null;

    public function __construct(Collection $parameters)
    {
        $this->parameters = $parameters;
    }

    abstract public function validate(): void;

    abstract public function execute(): void;

    public function getResults(): ?string
    {
        return $this->results;
    }

    abstract static function getAlias(): string;
}