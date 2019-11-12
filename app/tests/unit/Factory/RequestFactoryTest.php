<?php


namespace unit\Factory;

use App\Factory\RequestFactory;
use Container\Process\ProcessContext;
use Mockery;
use PHPUnit\Framework\TestCase;
use Request\Request;

class RequestFactoryTest extends TestCase
{

    public function testShouldInvokeRequestFactory()
    {
        $parameters = [];
        $requestFactory = new RequestFactory($parameters);
        $processContext = Mockery::mock(ProcessContext::class);
        $results = $requestFactory->__invoke($processContext);

        $this->assertInstanceOf(Request::class, $results);
    }
}