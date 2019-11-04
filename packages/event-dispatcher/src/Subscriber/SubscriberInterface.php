<?php

namespace EventManager\Subscriber;

interface SubscriberInterface
{
    public function getSubscribedEvents(): array;
}