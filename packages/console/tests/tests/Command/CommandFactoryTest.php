<?php


namespace tests\Command;

use Console\Command\CommandFactory;
use fixture\TestCommand;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class CommandFactoryTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldFactoryCommand()
    {
        $parameters = [
            'test' => 'test'
        ];
        $testCommand = CommandFactory::getInstance(TestCommand::class, $parameters);

        $this->assertInstanceOf(TestCommand::class, $testCommand);
        $this->assertEquals(true, $testCommand());

        $reflection = new ReflectionClass($testCommand);
        $parametersProperty = $reflection->getProperty('parameters');
        $parametersProperty->setAccessible(true);

        $this->assertEquals($parameters, $parametersProperty->getValue($testCommand));
    }
}