<?php


namespace unit\Factory;

use App\Factory\JsonErrorResponseFactory;
use Container\Process\ProcessContext;
use Mockery;
use PHPUnit\Framework\TestCase;
use Response\Response;

class JsonErrorResponseFactoryTest extends TestCase
{
    public function testShouldInvokeErrorResponseFactorySuccessWithJsonContext()
    {
        $mockException = Mockery::mock()
            ->shouldReceive('getMessage')
            ->andReturn('exception message')
            ->getMock()
            ->shouldReceive('getTraceAsString')
            ->andReturn('traceAsString')
            ->getMock();

        $processContextMock = Mockery::mock(ProcessContext::class)
            ->shouldReceive('get')
            ->with('containerException')
            ->once()
            ->andReturn($mockException)
            ->getMock()
            ->shouldReceive('remove')
            ->with('containerException')
            ->once()
            ->getMock();

        $errorResponseFactory = new JsonErrorResponseFactory([]);
        $response = $errorResponseFactory->__invoke($processContextMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('{"success":false,"errors":["exception message traceAsString"],"data":[]}', $response->getContents());
        $this->assertEquals(500, $response->getCode());
        $this->assertEquals([], $response->getHeaders());
    }
}