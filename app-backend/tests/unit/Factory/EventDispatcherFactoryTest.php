<?php


namespace unit\Factory;

use App\Factory\EventDispatcherFactory;
use Container\Process\ProcessContext;
use EventManager\Event\Event;
use EventManager\EventDispatcher;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;
use Router\Route;

class EventDispatcherFactoryTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldInvokeEventDispatcherFactory()
    {
        $parameters = [];
        $routes = [];
        $eventDispatcherFactory = new EventDispatcherFactory($parameters);

        $routerMock = Mockery::mock(Route::class)
            ->shouldReceive('getRoutes')
            ->once()
            ->andReturn($routes)
            ->getMock();
        $eventMock = Mockery::mock(Event::class);

        $processContextMock = Mockery::mock(ProcessContext::class)
            ->shouldReceive('get')
            ->with('router')
            ->andReturn($routerMock)
            ->getMock()
            ->shouldReceive('get')
            ->with('event')
            ->andReturn($eventMock)
            ->getMock();

        $results = $eventDispatcherFactory->__invoke($processContextMock);


        $eventProperty = new ReflectionProperty($results, 'event');
        $eventProperty->setAccessible(true);
        $event = $eventProperty->getValue($results);

        $this->assertInstanceOf(EventDispatcher::class, $results);
        $this->assertEquals($eventMock, $event);
    }
}