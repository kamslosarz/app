<?php

namespace unit\Factory;

use App\Factory\ResponseFactory;
use Container\Process\ProcessContext;
use EventManager\Event\Context;
use EventManager\Event\Event;
use EventManager\EventDispatcher;
use EventManager\EventManagerException;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;
use Request\Request;
use Response\Response;

class ResponseFactoryTest extends TestCase
{
    /**
     * @throws EventManagerException
     * @throws ReflectionException
     */
    public function testShouldInvokeResponseFactory()
    {
        $parameters = [];
        $responseFactory = new ResponseFactory($parameters);

        $requestMock = Mockery::mock(Request::class)
            ->shouldReceive('saveSessionAndCookie')
            ->once()
            ->getMock();

        $eventContextMock = Mockery::mock(Context::class)
            ->shouldReceive('get')
            ->with('responseHeaders', [])
            ->once()
            ->andReturn([])
            ->getMock()
            ->shouldReceive('get')
            ->with('responseCode', 200)
            ->once()
            ->andReturn(200)
            ->getMock();

        $eventDispatcherMock = Mockery::mock(EventDispatcher::class)
            ->shouldReceive('dispatch')
            ->once()
            ->getMock();

        $eventMock = Mockery::mock(Event::class)
            ->shouldReceive('getContext')
            ->once()
            ->andReturn($eventContextMock)
            ->getMock()
            ->shouldReceive('getStringResults')
            ->once()
            ->andReturn('event dispatch results')
            ->getMock();

        $processContextMock = Mockery::mock(ProcessContext::class)
            ->shouldReceive('get')
            ->with('eventDispatcher')
            ->once()
            ->andReturn($eventDispatcherMock)
            ->getMock()
            ->shouldReceive('get')
            ->with('event')
            ->once()
            ->andReturn($eventMock)
            ->getMock()
            ->shouldReceive('get')
            ->with('request')
            ->andReturns($requestMock)
            ->once()
            ->getMock();

        $results = $responseFactory->__invoke($processContextMock);

        $contentsProperty = new ReflectionProperty($results, 'contents');
        $contentsProperty->setAccessible(true);
        $contents = $contentsProperty->getValue($results);

        $headersProperty = new ReflectionProperty($results, 'headers');
        $headersProperty->setAccessible(true);
        $headers = $headersProperty->getValue($results);

        $codeProperty = new ReflectionProperty($results, 'code');
        $codeProperty->setAccessible(true);
        $code = $codeProperty->getValue($results);

        $this->assertInstanceOf(Response::class, $results);
        $this->assertEquals(200, $code);
        $this->assertEquals([], $headers);
        $this->assertEquals('event dispatch results', $contents);
    }
}