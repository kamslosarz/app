<?php

namespace App\Factory;

use Container\Process\Process;
use Container\Process\ProcessContext;
use Router\Router;

class RouterFactory extends Process
{
    public function & __invoke(ProcessContext $processContext): Router
    {
        $router = new Router($this->parameters);

        return $router;
    }
}