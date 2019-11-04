<?php

namespace EventManager\Listener;

use EventManager\Event\Event;

class ListenerInvokable
{
    private $listener;
    private $event;

    public function __construct($listener, Event &$event)
    {
        $this->listener = $listener;
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function __invoke()
    {
        if(is_array($this->listener))
        {
            list($invoker, $method) = $this->listener;
        }
        else
        {
            $invoker = $this->listener;
        }

        $context = $this->event->getContext();
        if(!is_object($invoker) && isset($method))
        {
            $invoker = new $invoker($context);
            if($context->hasParameters())
            {
                return $invoker->$method(...$context->getParameters());
            }

            return $invoker->$method();
        }
        elseif(is_object($invoker) && isset($method))
        {
            if($context->hasParameters())
            {
                return $invoker->$method(...$context->getParameters());
            }

            return $invoker->$method();
        }

        if($context->hasParameters())
        {
            return $invoker($context, ...$context->getParameters());
        }

        return $invoker($context);
    }
}