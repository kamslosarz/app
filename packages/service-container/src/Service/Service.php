<?php

namespace ServiceContainer\Service;

abstract class Service
{
    protected $config = [];

    abstract public function __invoke();
}