<?php

namespace ServiceContainer\ServiceResolver;

use ServiceContainer\Service\Service;
use ServiceContainer\ServiceContainer;
use ServiceContainer\ServiceContainerException;

class ServiceResolver
{
    private $servicesMap;
    private $serviceContainer;

    public function __construct(array $servicesMap, ServiceContainer $serviceContainer)
    {
        $this->servicesMap = $servicesMap;
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param $serviceName
     * @return Service
     * @throws ServiceContainerException
     */
    public function __invoke($serviceName): Service
    {
        if(!isset($this->servicesMap[$serviceName]))
        {
            throw new ServiceContainerException(sprintf('Service \'%s\' not found', $serviceName));
        }

        list($class, $parameters) = $this->servicesMap[$serviceName];
        $this->resolveServicesInParameters($parameters);

        return $this->factoryService($class, $parameters);
    }

    /**
     * @param array $parameters
     * @throws ServiceContainerException
     */
    private function resolveServicesInParameters(array &$parameters = []): void
    {
        foreach($parameters as &$parameter)
        {
            if($this->startsWithAt($parameter))
            {
                $parameter = $this->serviceContainer->getService(substr($parameter, 1, strlen($parameter)));
            }
        }
    }

    private function factoryService($class, $parameters): Service
    {
        return new $class(...$parameters);
    }

    private function startsWithAt($parameter): bool
    {
        return is_string($parameter) && substr($parameter, 0, 1) === '@';
    }
}