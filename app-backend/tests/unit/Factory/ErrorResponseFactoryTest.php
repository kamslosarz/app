<?php


namespace unit\Factory;

use App\Factory\ErrorResponseFactory;
use Container\Process\ProcessContext;
use EventManager\Event\Context;
use EventManager\Event\Event;
use Mockery;
use PHPUnit\Framework\TestCase;
use Response\Response;
use View\View;
use View\ViewException;

class ErrorResponseFactoryTest extends TestCase
{
    /**
     * @throws ViewException
     */
    public function testShouldInvokeErrorResponseFactorySuccess()
    {
        $mockException = Mockery::mock(ViewException::class);
        $processContextMock = Mockery::mock(ProcessContext::class)
            ->shouldReceive('get')
            ->with('containerException')
            ->once()
            ->andReturn($mockException)
            ->getMock()
            ->shouldReceive('remove')
            ->with('containerException')
            ->once()
            ->getMock()
            ->shouldReceive('get')
            ->with('view')
            ->andReturn(
                Mockery::mock(View::class)
                    ->shouldReceive('render')
                    ->with('error/index.phtml', [
                        'exception' => $mockException,
                        'json' => false,
                    ])
                    ->andReturn('error contents')
                    ->once()
                    ->getMock()
            )
            ->getMock()
            ->shouldReceive('get')
            ->with('event')
            ->andReturnNull()
            ->getMock();

        $errorResponseFactory = new ErrorResponseFactory([]);
        $response = $errorResponseFactory->__invoke($processContextMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('error contents', $response->getContents());
        $this->assertEquals(500, $response->getCode());
        $this->assertEquals([], $response->getHeaders());
    }

    public function testShouldInvokeErrorResponseFactorySuccessWithJsonContext()
    {
        $mockException = Mockery::mock(ViewException::class);
        $processContextMock = Mockery::mock(ProcessContext::class)
            ->shouldReceive('get')
            ->with('containerException')
            ->once()
            ->andReturn($mockException)
            ->getMock()
            ->shouldReceive('remove')
            ->with('containerException')
            ->once()
            ->getMock()
            ->shouldReceive('get')
            ->with('view')
            ->andReturn(
                Mockery::mock(View::class)
                    ->shouldReceive('render')
                    ->with('error/index.phtml', [
                        'exception' => $mockException,
                        'json' => true,
                    ])
                    ->andReturn('error contents')
                    ->once()
                    ->getMock()
            )
            ->getMock()
            ->shouldReceive('get')
            ->with('event')
            ->andReturns(
                Mockery::mock(Event::class)
                    ->shouldReceive('getContext')
                    ->andReturns(
                        Mockery::mock(Context::class)
                            ->shouldReceive('get')
                            ->with('jsonResponse', false)
                            ->andReturnTrue()
                            ->getMock()
                    )
                    ->getMock()
            )
            ->getMock();

        $errorResponseFactory = new ErrorResponseFactory([]);
        $response = $errorResponseFactory->__invoke($processContextMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('error contents', $response->getContents());
        $this->assertEquals(500, $response->getCode());
        $this->assertEquals([], $response->getHeaders());
    }
}