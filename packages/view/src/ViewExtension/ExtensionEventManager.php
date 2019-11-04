<?php


namespace View\ViewExtension;

use EventManager\EventManager;

class ExtensionEventManager extends EventManager
{
    public function hasListener($name): bool
    {
        return isset($this->listeners[$name]);
    }
}