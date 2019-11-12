<?php


namespace Router;

use EventManager\Subscriber\SubscriberInterface;

class RouteSubscriber implements SubscriberInterface
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function getSubscribedEvents(): array
    {
        return $this->routes;
    }
}