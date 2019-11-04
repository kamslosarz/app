<?php


use Collection\Collection;
use FlashMessenger\FlashMessenger;
use PHPUnit\Framework\TestCase;
use Request\Request;

class FlashMessengerTest extends TestCase
{
    public function testShouldConstructFlashMessenger()
    {
        $requestMock = Mockery::mock(Request::class);
        $flashMessenger = new FlashMessenger($requestMock);

        $this->assertInstanceOf(FlashMessenger::class, $flashMessenger);
    }

    public function testShouldAddMessagesToStorage()
    {
        $sessionMock = Mockery::mock(Collection::class)
            ->shouldReceive('add')
            ->with(sprintf('MESSAGES_%s', FlashMessenger::TYPE_INFO), 'message content')
            ->getMock();

        $requestMock = Mockery::mock(Request::class)
            ->shouldReceive('getSession')
            ->andReturn($sessionMock)
            ->getMock();

        $flashMessenger = new FlashMessenger($requestMock);
        $results = $flashMessenger->add('message content', FlashMessenger::TYPE_INFO);

        $sessionMock->shouldHaveReceived('add')->once();

        $this->assertEquals($results, $flashMessenger);
    }

    public function testShouldGetMessage()
    {
        $messages = [
            'm1',
            'm2'
        ];
        $sessionMock = Mockery::mock(Collection::class)
            ->shouldReceive('get')
            ->with('MESSAGES_INFO', [])
            ->andReturn($messages)
            ->getMock();

        $requestMock = Mockery::mock(Request::class)
            ->shouldReceive('getSession')
            ->andReturn($sessionMock)
            ->getMock();

        $flashMessenger = new FlashMessenger($requestMock);
        $results = $flashMessenger->get(FlashMessenger::TYPE_INFO);

        $this->assertEquals($messages, $results);
        $sessionMock->shouldHaveReceived('get')->once();
    }

    public function testShouldCheckIfHasMessagesSuccess()
    {
        $returns = [
            'MESSAGES_WARNING' => false,
            'MESSAGES_SUCCESS' => false,
            'MESSAGES_ERROR' => false,
            'MESSAGES_INFO' => true,
        ];
        $sessionMock = Mockery::mock(Collection::class)
            ->shouldReceive('has')
            ->andReturnUsing(function ($arg) use ($returns) {
                return $returns[$arg];
            })
            ->getMock();

        $requestMock = Mockery::mock(Request::class)
            ->shouldReceive('getSession')
            ->andReturn($sessionMock)
            ->getMock();

        $flashMessenger = new FlashMessenger($requestMock);
        $results = $flashMessenger->hasMessages();

        $this->assertTrue($results);
        $sessionMock->shouldHaveReceived('has')->times(4);
    }

    public function testShouldCheckIfHasMessagesFailed()
    {
        $sessionMock = Mockery::mock(Collection::class)
            ->shouldReceive('has')
            ->andReturnFalse()
            ->getMock();

        $requestMock = Mockery::mock(Request::class)
            ->shouldReceive('getSession')
            ->andReturn($sessionMock)
            ->getMock();

        $flashMessenger = new FlashMessenger($requestMock);
        $results = $flashMessenger->hasMessages();

        $this->assertFalse($results);
        $sessionMock->shouldHaveReceived('has')->times(4);
    }


    public function testShouldFlashMessages()
    {
        $expectedMessages = [
            'MESSAGES_ERROR' => [
                'error1', 'error2'
            ],
            'MESSAGES_SUCCESS' => [
                'success1', 'success2'
            ],
            'MESSAGES_INFO' => [
                'info1', 'info2'
            ],
            'MESSAGES_WARNING' => [
                'warn2', 'warn1'
            ]
        ];

        $sessionMock = Mockery::mock(Collection::class)
            ->shouldReceive('has')
            ->andReturnUsing(function ($arg) use ($expectedMessages) {

                return isset($expectedMessages[$arg]);
            })
            ->getMock()
            ->shouldReceive('get')
            ->andReturnUsing(function ($arg) use ($expectedMessages) {

                return $expectedMessages[$arg];
            })
            ->getMock()
            ->shouldReceive('remove')
            ->andReturnSelf()
            ->getMock();

        $requestMock = Mockery::mock(Request::class)
            ->shouldReceive('getSession')
            ->andReturn($sessionMock)
            ->getMock();

        $flashMessenger = new FlashMessenger($requestMock);

        $messages = $flashMessenger->flash();

        $this->assertEquals($expectedMessages, $messages);

        $sessionMock->shouldHaveReceived('has')->times(4);
        $sessionMock->shouldHaveReceived('get')->times(4);
        $sessionMock->shouldHaveReceived('remove')->times(4);
    }
}