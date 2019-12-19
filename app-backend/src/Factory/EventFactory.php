<?php


namespace App\Factory;

use App\ApplicationException;
use App\Context\AppContext;
use Container\Process\Process;
use Container\Process\ProcessContext;
use EventManager\Event\Event;
use Request\Request;
use Router\Exception\RouterException;
use Router\Router;
use ServiceContainer\ServiceContainer;

class EventFactory extends Process
{
    /**
     * @param ProcessContext $processContext
     * @return Event
     * @throws ApplicationException
     * @throws RouterException
     */
    public function & __invoke(ProcessContext $processContext): Event
    {
        /** @var Router $router */
        $router = $processContext->get('router');
        /** @var Request $request */
        $request = $processContext->get('request');
        $route = $router->getRoute($request);
        if (!isset($this->parameters['servicesMap'])) {
            throw new ApplicationException('Property "servicesMap" is not defined');
        }
        $view = $processContext->get('view');
        $serviceContainer = new ServiceContainer($this->parameters['servicesMap']);
        $event = new Event($route->getName(), new AppContext([
            'request' => &$request,
            'route' => &$route,
            'serviceContainer' => &$serviceContainer,
            'view' => &$view,
        ]));

        return $event;
    }
}