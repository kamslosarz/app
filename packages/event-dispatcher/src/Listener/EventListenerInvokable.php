<?php

namespace EventManager\Listener;

use EventManager\Event\Context;

interface EventListenerInvokable
{
    public function __construct(Context $context);
}