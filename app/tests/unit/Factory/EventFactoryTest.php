<?php

namespace unit\Factory;

use App\ApplicationException;
use App\Context\AppContext;
use App\Factory\EventFactory;
use Container\Process\ProcessContext;
use EventManager\Event\Event;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Request\Request;
use Router\Exception\RouterException;
use Router\Route;
use Router\Router;
use View\View;

class EventFactoryTest extends TestCase
{
    /**
     * @throws ApplicationException
     * @throws RouterException
     * @throws ReflectionException
     */
    public function testShouldInvokeEventFactoryTestSuccess()
    {
        $parameters = [
            'servicesMap' => [
            ]
        ];
        $eventFactory = new EventFactory($parameters);
        $viewMock = Mockery::mock(View::class);
        $requestMock = Mockery::mock(Request::class);
        $routeMock = Mockery::mock(Route::class)
            ->shouldReceive('getName')
            ->once()
            ->andReturn('route name')
            ->getMock();
        $processContextMock = Mockery::mock(ProcessContext::class)
            ->shouldReceive('get')
            ->with('router')
            ->andReturn(
                Mockery::mock(Router::class)
                    ->shouldReceive('getRoute')
                    ->once()
                    ->andReturn($routeMock)
                    ->getMock()
            )
            ->getMock()
            ->shouldReceive('get')
            ->with('request')
            ->andReturn($requestMock)
            ->getMock()
            ->shouldReceive('get')
            ->with('view')
            ->andReturn($viewMock)
            ->once()
            ->getMock();

        $event = $eventFactory->__invoke($processContextMock);

        $eventReflection = new ReflectionClass($event);
        $name = $eventReflection->getProperty('name');
        $name->setAccessible(true);
        $results = $eventReflection->getProperty('results');
        $results->setAccessible(true);
        $context = $eventReflection->getProperty('context');
        $context->setAccessible(true);
        $context = $context->getValue($event);

        $this->assertInstanceOf(Event::class, $event);
        $this->assertEquals('route name', $name->getValue($event));
        $this->assertInstanceOf(AppContext::class, $context);
        $this->assertEquals($viewMock, $context->get('view'));
        $this->assertEquals($requestMock, $context->get('request'));
        $this->assertEquals($routeMock, $context->get('route'));
    }
}