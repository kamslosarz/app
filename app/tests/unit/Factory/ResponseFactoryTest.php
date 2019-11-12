<?php

namespace unit\Factory;

use App\Factory\ResponseFactory;
use Container\Process\ProcessContext;
use EventManager\EventManagerException;
use Mockery;
use PHPUnit\Framework\TestCase;

class ResponseFactoryTest extends TestCase
{
    /**
     * @throws EventManagerException
     */
    public function testShouldInvokeResponseFactory()
    {
        $parameters = [];
        $responseFactory = new ResponseFactory($parameters);

        $processContextMock = Mockery::mock(ProcessContext::class);
        $results = $responseFactory->__invoke($processContextMock);

        //$this->assertInstanceOf(Response::class, $results);
    }
}