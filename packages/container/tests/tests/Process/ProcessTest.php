<?php

namespace tests\Process;

use Container\Process\Process;
use Container\Process\ProcessContext;
use fixture\Process\ExampleProcess;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class ProcessTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructProcess()
    {
        $parameters = [
            'p1', 'p2'
        ];
        $process = Mockery::mock(Process::class, [$parameters]);

        $reflection = new ReflectionClass($process);
        $parametersProperty = $reflection->getProperty('parameters');
        $parametersProperty->setAccessible(true);

        $this->assertEquals($parameters, $parametersProperty->getValue($process));
    }

    public function testShouldInvokeProcess()
    {
        $process = new ExampleProcess([
            'p1', 'p2'
        ]);

        $processContextMock = Mockery::mock(ProcessContext::class);
        $processReference = $process->__invoke($processContextMock);
        $this->assertEquals('processReference', $processReference);
    }
}