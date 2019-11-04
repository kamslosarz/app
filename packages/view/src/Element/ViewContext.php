<?php


namespace View\Element;

use EventManager\Event\Context;

class ViewContext extends Context
{
    public function getParameters(): array
    {
        return $this->__toArray();
    }

    public function hasParameters(): bool
    {
        return true;
    }
}