<?php

namespace App\Context;

use EventManager\Event\Context;

class AppContext extends Context
{
    public function getParameters(): array
    {
        return $this->get('route')->getParameters();
    }

    public function hasParameters(): bool
    {
        return true;
    }
}