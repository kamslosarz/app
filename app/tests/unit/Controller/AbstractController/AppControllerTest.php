<?php

namespace unit\Controller\AbstractController;

use App\Controller\AbstractController\AppController;
use EventManager\Event\Context;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

class AppControllerTest extends TestCase
{
    /**
     * @throws ReflectionException
     * @doesNotPerformAssertions
     */
    public function testShouldInvokeRedirectMethod()
    {
        $url = '/test/redirect';
        $code = '304';
        $contextMock = Mockery::mock(Context::class)
            ->shouldReceive('set')
            ->with('responseHeaders', [sprintf('Location: %s', $url)])
            ->once()
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('set')
            ->with('responseCode', $code)
            ->once()
            ->getMock();

        /** @var AppController $appController */
        $appController = Mockery::mock(AppController::class, [
            $contextMock
        ])
            ->makePartial();

        $redirectMethod = new ReflectionMethod($appController, 'redirect');
        $redirectMethod->setAccessible(true);
        $redirectMethod->invokeArgs($appController, [$url, $code]);

        $contextMock->shouldHaveReceived('set')->twice();
    }
}