<?php

namespace View\ViewExtension;

use EventManager\Subscriber\SubscriberInterface;

abstract class ViewExtension implements SubscriberInterface
{
    public function getSubscribedEvents(): array
    {
        return $this->getFunctions();
    }

    abstract protected function getFunctions(): array;
}