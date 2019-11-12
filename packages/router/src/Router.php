<?php

namespace Router;

use Request\Request;
use Router\Exception\RouterException;

class Router
{
    const REQUEST_METHOD_PATTERN = '/^[a-zA-Z\,]+\:/';
    const REQUEST_PARAMETER_PATTER = '/^\{[a-zA-Z0-9]+\}$|^\*$/';
    const DEFAULT_REQUEST_METHOD = 'get';

    protected array $routes;

    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * @param Request $request
     * @return Route
     * @throws RouterException
     */
    public function getRoute(Request $request): Route
    {
        foreach($this->routes as $routeName => $routeProperties)
        {
            $requestParameters = [];

            if($this->isRouteMethodMatch($routeName, strtolower($request->getRequestMethod())))
            {
                if($this->tryMatch($request, $routeName, $requestParameters))
                {
                    return new Route($routeName, $requestParameters);
                }
            }
        }

        if(isset($this->routes['*']))
        {
            return new Route('*');
        }

        throw new RouterException(sprintf('Route for "%s:%s" not found', strtolower($request->getRequestMethod()), $request->getRequestUri()));
    }

    private function tryMatch(Request $request, string $routeName, array &$parameters = []): bool
    {
        $requestUri = explode('/', $request->getRequestUri());
        $route = explode('/', preg_replace(self::REQUEST_METHOD_PATTERN, '', $routeName));

        foreach($requestUri as $index => $requestParameter)
        {
            if(isset($route[$index]))
            {
                $routeParameter = $route[$index];
                if($requestParameter !== $routeParameter)
                {
                    if(!$this->isParameterVariable($routeParameter))
                    {
                        return false;
                    }
                    $parameters[] = $requestParameter;
                }
            }
            else
            {
                return false;
            }
        }

        return true;
    }

    private function isParameterVariable($parameter): bool
    {
        return preg_match(self::REQUEST_PARAMETER_PATTER, $parameter);
    }

    private function isRouteMethodMatch(string $routeName, $requestMethod): bool
    {
        $requestMethods = [];
        preg_match(self::REQUEST_METHOD_PATTERN, $routeName, $requestMethods);
        if(!isset($requestMethods[0]) || empty($requestMethods[0]))
        {
            return $requestMethod === self::DEFAULT_REQUEST_METHOD;
        }
        $requestMethods = explode(',', rtrim($requestMethods[0], ":"));

        return in_array($requestMethod, $requestMethods);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}