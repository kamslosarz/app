<?php


namespace tests\Command;

use Console\Command\Command;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class CommandTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructCommand()
    {
        $parameters = [
            'test' => 'value'
        ];
        $commandMock = Mockery::mock(Command::class, [$parameters])
            ->makePartial();

        $reflection = new ReflectionClass($commandMock);
        $parametersProperty = $reflection->getProperty('parameters');
        $parametersProperty->setAccessible(true);

        $this->assertEquals($parameters, $parametersProperty->getValue($commandMock));
    }
}