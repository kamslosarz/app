<?php

namespace EventManager\Event;

use Collection\Collection;

abstract class Context extends Collection
{
    abstract public function getParameters(): array;

    abstract public function hasParameters(): bool;
}