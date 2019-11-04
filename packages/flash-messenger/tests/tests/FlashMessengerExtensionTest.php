<?php


use FlashMessenger\FlashMessenger;
use FlashMessenger\FlashMessengerExtension;
use PHPUnit\Framework\TestCase;
use Request\Request;

class FlashMessengerExtensionTest extends TestCase
{
    public function testShouldConstructFlashMessengerExtension()
    {
        $requestMock = Mockery::mock(Request::class);
        $flashMessengerMock = Mockery::mock(FlashMessenger::class, [$requestMock]);
        $flashMessengerExtension = new FlashMessengerExtension($flashMessengerMock);

        $this->assertInstanceOf(FlashMessengerExtension::class, $flashMessengerExtension);
    }

    public function testShouldGetSubscribedEvents()
    {
        $requestMock = Mockery::mock(Request::class);
        $flashMessengerMock = Mockery::mock(FlashMessenger::class, [$requestMock]);
        $flashMessengerExtension = new FlashMessengerExtension($flashMessengerMock);

        $subscribedEvents = $flashMessengerExtension->getSubscribedEvents();

        $this->assertEquals([
            'flashMessages' => [
                [$flashMessengerExtension, 'flashMessages']
            ],
            'hasMessagesToFlash' => [
                [$flashMessengerExtension, 'hasMessagesToFlash']
            ]
        ], $subscribedEvents);
    }

    public function testShouldFlashMessages()
    {
        $expectedMessages = [
            'm1',
            'm2'
        ];
        $flashMessengerMock = Mockery::mock(FlashMessenger::class)
            ->shouldReceive('flash')
            ->andReturn($expectedMessages)
            ->getMock();

        $flashMessengerExtension = new FlashMessengerExtension($flashMessengerMock);

        $messages = $flashMessengerExtension->flashMessages();
        $this->assertEquals($expectedMessages, $messages);
        $flashMessengerMock->shouldHaveReceived('flash')->once();
    }


    public function testShouldCheckIfAnyOfMessagesToFlashExists()
    {
        $flashMessengerMock = Mockery::mock(FlashMessenger::class)
            ->shouldReceive('hasMessages')
            ->andReturnFalse()
            ->getMock();

        $flashMessengerExtension = new FlashMessengerExtension($flashMessengerMock);

        $this->assertFalse($flashMessengerExtension->hasMessagesToFlash());
        $flashMessengerMock->shouldHaveReceived('hasMessages')->once();
    }
}