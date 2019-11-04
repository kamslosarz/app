<?php

use fixture\TestService\TestService;
use PHPUnit\Framework\TestCase;
use ServiceContainer\ServiceContainer;
use ServiceContainer\ServiceContainerException;
use ServiceContainer\ServiceResolver\ServiceResolver;

class ServiceResolverTest extends TestCase
{
    /**
     * @throws ServiceContainerException
     */
    public function testShouldConstructServiceResolver()
    {
        $servicesMap = [
            'testService' => [
                TestService::class, [
                    'param1', 'param2'
                ]
            ]
        ];
        $serviceContainer = Mockery::mock(ServiceContainer::class);

        $serviceResolver = new ServiceResolver($servicesMap, $serviceContainer);
        $service = $serviceResolver->__invoke('testService');

        $this->assertEquals($service, $service);
    }

    /**
     * @throws ServiceContainerException
     */
    public function testShouldThrowServiceContainerExceptionOnUnknownService()
    {
        $this->expectException(ServiceContainerException::class);
        $this->expectExceptionMessage('Service \'test service name\' not found');

        $serviceContainer = Mockery::mock(ServiceContainer::class);
        $serviceResolver = new ServiceResolver([], $serviceContainer);
        $serviceResolver->__invoke('test service name');
    }

    /**
     * @throws ReflectionException
     * @throws ServiceContainerException
     */
    public function testShouldResolveServiceDependencies()
    {
        $servicesMap = include dirname(dirname(__DIR__)).'/fixture/services.php';

        $serviceContainerMock = Mockery::mock(ServiceContainer::class)
            ->shouldReceive('getService')
            ->with('testService')
            ->andReturn(Mockery::mock(TestService::class))
            ->getMock();

        $serviceResolver = new ServiceResolver($servicesMap, $serviceContainerMock);
        $service = $serviceResolver->__invoke('testServiceWthDependency');

        $this->assertInstanceOf(TestService::class, $service);

        $reflection = new ReflectionClass($service);
        $testServiceProperty = $reflection->getProperty('testService');
        $testServiceProperty->setAccessible(true);

        $this->assertInstanceOf(TestService::class, $testServiceProperty->getValue($service));
    }

    /**
     * @throws ServiceContainerException
     */
    public function testShouldThrowExceptionWhenDependencyServiceNotExists()
    {
        $this->expectException(ServiceContainerException::class);
        $this->expectExceptionMessage('Service \'dependencyService\' not found');

        $servicesMap = [
            'testService' => [
                TestService::class, [
                    '@dependencyService'
                ]
            ],
        ];

        $serviceContainerMock = Mockery::mock(ServiceContainer::class);
        $serviceResolver = new ServiceResolver($servicesMap, $serviceContainerMock);
        $serviceContainerMock
            ->shouldReceive('getService')
            ->andReturnUsing(function ($serviceName) use ($serviceResolver) {
                return $serviceResolver->__invoke($serviceName);
            })
            ->getMock();

        $serviceResolver->__invoke('testService');
    }
}