<?php

namespace ServiceContainer\Service;

abstract class Service
{
    protected array $config = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
}