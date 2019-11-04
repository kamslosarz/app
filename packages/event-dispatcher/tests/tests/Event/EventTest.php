<?php

namespace tests\Event;

use EventManager\Event\Context;
use EventManager\Event\Event;
use Mockery;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testShouldConstructEventAndGetName()
    {
        $event = new Event('test event name', Mockery::mock(Context::class));

        $this->assertEquals('test event name', $event->getName());
    }

    public function testShouldConstructEventAndGetContext()
    {
        $event = new Event('eventName',
            Mockery::mock(Context::class)
                ->shouldReceive('__toArray')
                ->andReturn(['test' => 'test123'])
                ->getMock()
        );

        $this->assertInstanceOf(Context::class, $event->getContext());
        $this->assertEquals(['test' => 'test123'], $event->getContext()->__toArray());
    }

    public function testShouldGetEventResults()
    {
        $results = 'event invoke results';
        $event = new Event('example event', Mockery::mock(Context::class));
        $event->addResults($results);

        $this->assertEquals($results, $event->getStringResults());
    }

    public function testShouldGetContext()
    {
        $event = new Event('eventName', Mockery::mock(Context::class));

        $this->assertInstanceOf(Context::class, $event->getContext());
    }

    public function testShouldAddEventResults()
    {
        $event = new Event('name', Mockery::mock(Context::class));
        $event->addResults('test results');

        $this->assertEquals('test results', $event->getStringResults());
    }
}