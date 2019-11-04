<?php

namespace EventManager;

use EventManager\Subscriber\SubscriberInterface;

class EventManager
{
    protected $listeners = [];

    /**
     * @param SubscriberInterface $subscriber
     * @return EventManager
     */
    public function addSubscriber(SubscriberInterface $subscriber): self
    {
        foreach($subscriber->getSubscribedEvents() as $eventName => $listeners)
        {
            foreach($listeners as $listener)
            {
                $this->addListener($eventName, $listener);
            }
        }

        return $this;
    }

    /**
     * @param string $eventName
     * @param $listener
     * @return EventManager
     */
    public function addListener(string $eventName, $listener): self
    {
        $this->listeners[$eventName][] = $listener;

        return $this;
    }

    public function getListeners(): array
    {
        return $this->listeners;
    }
}