<?php

namespace EventManager;

use EventManager\Event\Event;
use EventManager\Listener\ListenerInvokable;

class EventDispatcher
{
    private EventManager $eventManager;
    private Event $event;

    public function __construct(EventManager $eventManager, Event &$event)
    {
        $this->eventManager = $eventManager;
        $this->event = $event;
    }

    /**
     * @throws EventManagerException
     */
    public function dispatch()
    {
        foreach($this->eventManager->getListeners() as $eventName => $listeners)
        {
            if($this->isEventMatch($eventName))
            {
                foreach($listeners as $listener)
                {
                    if(!is_callable($listener))
                    {
                        throw new EventManagerException(sprintf(
                            'listener "%s" for event "%s" is not callable', print_r($listener, true), $eventName
                        ));
                    }

                    $listenerInvokable = $this->getListenerInvokable($listener);
                    $results = $listenerInvokable();
                    if($results)
                    {
                        $this->event->addResults($results);
                    }
                }
            }
        }
    }

    protected function isEventMatch(string $eventName): bool
    {
        return $this->event->getName() === $eventName;
    }

    private function getListenerInvokable($listener)
    {
        return new ListenerInvokable($listener, $this->event);
    }
}