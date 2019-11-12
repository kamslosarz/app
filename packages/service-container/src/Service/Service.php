<?php

namespace ServiceContainer\Service;

abstract class Service
{
    protected array $config = [];

    abstract public function __invoke();
}