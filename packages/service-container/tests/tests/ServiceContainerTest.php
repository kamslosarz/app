<?php

use Collection\Collection;
use PHPUnit\Framework\TestCase;
use ServiceContainer\Service\Service;
use ServiceContainer\ServiceContainer;
use ServiceContainer\ServiceContainerException;
use ServiceContainer\ServiceResolver\ServiceResolver;

class ServiceContainerTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructServiceContainer()
    {
        $serviceMap = [
            'testService'
        ];
        $serviceContainer = new ServiceContainer($serviceMap);

        $reflection = new ReflectionClass($serviceContainer);
        $serviceCollection = $reflection->getProperty('serviceCollection');
        $serviceCollection->setAccessible(true);

        $serviceResolver = $reflection->getProperty('serviceResolver');
        $serviceResolver->setAccessible(true);

        $this->assertInstanceOf(Collection::class, $serviceCollection->getValue($serviceContainer));
        $this->assertInstanceOf(ServiceResolver::class, $serviceResolver->getValue($serviceContainer));
    }

    /**
     * @throws ReflectionException
     * @throws ServiceContainerException
     */
    public function testShouldGetServiceFirstTime()
    {
        $serviceMap = [
            'testService' => [
                'service class', [
                    'service parameters'
                ]
            ]
        ];
        $testService = Mockery::mock(Service::class);
        /** @var ServiceContainer $serviceContainer */
        $serviceContainer = Mockery::mock(ServiceContainer::class, [$serviceMap])
            ->makePartial();
        $serviceResolverMock = Mockery::mock(ServiceResolver::class)
            ->shouldReceive('__invoke')
            ->with('testService')
            ->andReturns($testService)
            ->getMock();
        $serviceCollectionMock = Mockery::mock(Collection::class)
            ->shouldReceive('has')
            ->with('testService')
            ->andReturnFalse()
            ->getMock()
            ->shouldReceive('set')
            ->with('testService', $testService)
            ->getMock()
            ->shouldReceive('get')
            ->with('testService')
            ->andReturn($testService)
            ->getMock();

        $this->setMockProperty($serviceContainer, 'serviceResolver', $serviceResolverMock);
        $this->setMockProperty($serviceContainer, 'serviceCollection', $serviceCollectionMock);

        /** @var Service $service */
        $service = $serviceContainer->getService('testService');

        $this->assertEquals($testService, $service);
        $serviceCollectionMock->shouldHaveReceived('has');
        $serviceCollectionMock->shouldHaveReceived('set')->with('testService', $testService);
        $serviceCollectionMock->shouldHaveReceived('get')->with('testService');
        $serviceResolverMock->shouldHaveReceived('__invoke')->with('testService');
    }

    /**\
     * @param $serviceContainer
     * @param string $propertyName
     * @param $propertyValue
     * @throws ReflectionException
     */
    private function setMockProperty($serviceContainer, string $propertyName, $propertyValue)
    {
        $reflection = new ReflectionClass($serviceContainer);
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($serviceContainer, $propertyValue);
    }
}