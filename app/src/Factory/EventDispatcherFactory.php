<?php

namespace App\Factory;

use Container\Process\Process;
use Container\Process\ProcessContext;
use EventManager\Event\Event;
use EventManager\EventDispatcher;
use EventManager\EventManager;
use Router\Router;
use Router\RouteSubscriber;

class EventDispatcherFactory extends Process
{
    /**
     * @param ProcessContext $processContext
     * @return EventDispatcher
     */
    public function & __invoke(ProcessContext $processContext)
    {
        /** @var Router $router */
        $router = $processContext->get('router');
        $eventManager = new EventManager();
        $eventManager->addSubscriber(new RouteSubscriber($router->getRoutes()));
        /** @var Event $event */
        $event = $processContext->get('event');
        $eventDispatcher = new EventDispatcher($eventManager, $event);

        return $eventDispatcher;
    }
}