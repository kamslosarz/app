<?php

use EventManager\Event\Context;
use EventManager\Event\Event;
use EventManager\EventDispatcher;
use EventManager\EventManager;
use EventManager\EventManagerException;
use fixture\ExampleClass;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testShouldConstructEventDispatcher()
    {
        $eventMock = Mockery::mock(Event::class);
        $eventDispatcher = new EventDispatcher(Mockery::mock(EventManager::class), $eventMock);
        $this->assertInstanceOf(EventDispatcher::class, $eventDispatcher);
    }

    /**
     * @throws EventManagerException
     * @doesNotPerformAssertions
     */
    public function testShouldInvokeListener()
    {
        $eventManagerMock = Mockery::mock(EventManager::class)
            ->shouldReceive('getListeners')
            ->andReturn([
                'testEvent' => [
                    function () {
                        return 'test listener called';
                    }
                ]
            ])
            ->getMock();

        $event = Mockery::mock(Event::class);
        $event->shouldReceive('getName')
            ->once()
            ->andReturn('testEvent')
            ->getMock()
            ->shouldReceive('addResults')
            ->once()
            ->with('test listener called')
            ->getMock()
            ->shouldReceive('getContext')
            ->andReturn(
                Mockery::mock(Context::class)
                    ->shouldReceive('hasParameters')
                    ->andReturnTrue()
                    ->getMock()
                    ->shouldReceive('getParameters')
                    ->andReturn([])
                    ->getMock()
            )
            ->getMock();

        $eventDispatcher = new EventDispatcher($eventManagerMock, $event);
        $eventDispatcher->dispatch();

        $event->shouldHaveReceived('addResults');
        $event->shouldHaveReceived('getName');
    }

    /**
     * @throws EventManagerException
     * @doesNotPerformAssertions
     */
    public function testShouldInvokeMultipleListeners()
    {
        $listeners = [
            'testEvent' => [
                function () {
                    return 'test listener #1 called';
                },
                function () {
                    return 'test listener #2 called';
                }
            ]
        ];
        $eventManagerMock = Mockery::mock(EventManager::class)
            ->shouldReceive('getListeners')
            ->andReturn($listeners)
            ->getMock();
        $event = Mockery::mock(Event::class);
        $event->shouldReceive('getName')
            ->andReturn('testEvent')
            ->once()
            ->getMock()
            ->shouldReceive('addResults')
            ->with('test listener #1 called')
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('addResults')
            ->with('test listener #2 called')
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('getContext')
            ->andReturn(
                Mockery::mock(Context::class)
                    ->shouldReceive('getParameters')
                    ->andReturn([])
                    ->getMock()
                    ->shouldReceive('hasParameters')
                    ->andReturnTrue()
                    ->getMock()
            )
            ->getMock();

        $eventDispatcher = new EventDispatcher($eventManagerMock, $event);
        $eventDispatcher->dispatch();

        $event->shouldHaveReceived('addResults')->times(2);
        $event->shouldHaveReceived('getName')->times(1);
    }

    /**
     * @throws EventManagerException
     * @doesNotPerformAssertions
     */
    public function testShouldInvokeListenerInExternalClass()
    {
        $eventManagerMock = Mockery::mock(EventManager::class)
            ->shouldReceive('getListeners')
            ->andReturn([
                'someActionEvent' => [
                    [ExampleClass::class, 'exampleMethod']
                ],
            ])
            ->getMock();
        $event = Mockery::mock(Event::class)
            ->shouldReceive('getName')
            ->andReturn('someActionEvent')
            ->getMock()
            ->shouldReceive('addResults')
            ->with('example method results')
            ->getMock()
            ->shouldReceive('getContext')
            ->andReturn(
                Mockery::mock(Context::class)
                    ->shouldReceive('hasParameters')
                    ->andReturnTrue()
                    ->getMock()
                    ->shouldReceive('getParameters')
                    ->andReturn([])
                    ->getMock()
            )
            ->getMock();

        $eventDispatcher = new EventDispatcher($eventManagerMock, $event);
        $eventDispatcher->dispatch();

        $event->shouldHaveReceived('addResults');
    }

    /**
     * @throws EventManagerException
     */
    public function testShouldThrowInvalidListenerException()
    {
        $this->expectException(EventManagerException::class);
        $eventManagerMock = Mockery::mock(EventManager::class)
            ->shouldReceive('getListeners')
            ->andReturn(['eventName' => ['invalid listener', 'invalid listener 2']])
            ->getMock();

        $eventMock = Mockery::mock(Event::class)
            ->shouldReceive('getName')
            ->andReturn('eventName')
            ->once()
            ->getMock();

        $eventDispatcher = new EventDispatcher($eventManagerMock, $eventMock);
        $eventDispatcher->dispatch();
    }
}