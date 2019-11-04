<?php

use EventManager\EventManager;
use EventManager\Subscriber\SubscriberInterface;
use PHPUnit\Framework\TestCase;

class EventManagerTest extends TestCase
{
    public function testShouldConstructEventManager()
    {
        $eventManager = new EventManager();
        $this->assertInstanceOf(EventManager::class, $eventManager);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldAddListener()
    {
        $eventManager = new EventManager();
        $listener = function () {
            return 'test listener called';
        };
        $eventManager->addListener('testEvent', $listener);
        $listeners = $this->getListeners($eventManager);

        $this->assertEquals(['testEvent' => [$listener]], $listeners);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldAddSubscriber()
    {
        $eventManager = new EventManager();
        $subscriber = Mockery::mock(SubscriberInterface::class);
        $subscriber->shouldReceive('getSubscribedEvents')
            ->andReturn([
                'testEventName' => [$subscriber, 'testEventListener'],
                'testEventName2' => [$subscriber, 'testEventListener'],
            ])
            ->getMock();
        $eventManager->addSubscriber($subscriber);
        $this->assertEquals($this->getListeners($eventManager), $this->getListeners($eventManager));
    }

    /**
     * @param EventManager $eventManager
     * @return mixed
     * @throws ReflectionException
     */
    public function getListeners(EventManager $eventManager)
    {
        $listeners = (new ReflectionClass($eventManager))->getProperty('listeners');
        $listeners->setAccessible(true);

        return $listeners->getValue($eventManager);
    }
}