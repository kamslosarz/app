<?php

namespace fixture\Process;

use Container\Process\Process;
use Container\Process\ProcessFactory;
use Factory\FactoryException;
use PHPUnit\Framework\TestCase;

class ProcessFactoryTest extends TestCase
{
    /**
     * @throws FactoryException
     */
    public function testShouldConstructProcess()
    {
        $process = ProcessFactory::getInstance([
            ExampleProcess::class,
            ['p1', 'p2']
        ]);

        $this->assertInstanceOf(Process::class, $process);
    }
}