<?php

namespace tests\Listener;

use EventManager\Event\Context;
use EventManager\Event\ContextException;
use EventManager\Event\Event;
use EventManager\Listener\ListenerInvokable;
use Mockery;
use PHPUnit\Framework\TestCase;

class ListenerInvokableTest extends TestCase
{
    /**
     * @throws ContextException
     */
    public function testShouldConstructAndInvokeListenerInvokableClosure()
    {
        /** @var callable $listenerMock */
        $listenerMock = Mockery::mock(function (Context $context, $param1, $param2) {
            $context->__toArray();

            return sprintf('invoked with %s %s', $param1, $param2);
        });
        /** @var Event $eventMock */
        $eventMock = Mockery::mock(Event::class)
            ->shouldReceive('getContext')
            ->andReturn(Mockery::mock(Context::class)
                ->shouldReceive('hasParameters')
                ->andReturnTrue()
                ->getMock()
                ->shouldReceive('getParameters')
                ->andReturns([
                    'param1', 'param2'
                ])
                ->getMock()
            )->getMock();

        $listenerInvokable = new ListenerInvokable($listenerMock, $eventMock);
        $results = $listenerInvokable->__invoke();

        $this->assertEquals('invoked with param1 param2', $results);
    }
}