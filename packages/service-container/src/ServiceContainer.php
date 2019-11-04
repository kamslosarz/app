<?php

namespace ServiceContainer;

use Collection\Collection;
use ServiceContainer\Service\Service;
use ServiceContainer\ServiceResolver\ServiceResolver;

class ServiceContainer
{
    protected $serviceCollection;
    protected $serviceResolver;

    public function __construct(array $servicesMap)
    {
        $this->serviceResolver = $this->getServiceResolver($servicesMap);
        $this->serviceCollection = $this->getServiceCollection();
    }

    /**
     * @param $name
     * @return Service
     * @throws ServiceContainerException
     */
    public function getService($name): Service
    {
        if(!$this->serviceCollection->has($name))
        {
            $this->serviceCollection->set($name, $this->resolveService($name));
        }

        return $this->serviceCollection->get($name);
    }

    /**
     * @param $name
     * @return Service
     * @throws ServiceContainerException
     */
    protected function resolveService($name): Service
    {
        return ($this->serviceResolver)($name);
    }

    protected function getServiceResolver($servicesMap): ServiceResolver
    {
        return new ServiceResolver($servicesMap, $this);
    }

    protected function getServiceCollection(): Collection
    {
        return new Collection();
    }
}