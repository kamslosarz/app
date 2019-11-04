<?php

use Console\Command\Command;
use Console\Command\CommandException;
use Console\Command\CommandLocator;
use PHPUnit\Framework\TestCase;

class CommandLocatorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructCommandLocator()
    {
        $input = 'some-command arg1 arg2';
        $namespace = 'TestFixture\\';
        $commandLocator = new CommandLocator($input, $namespace);

        $reflection = new ReflectionClass($commandLocator);
        $inputProperty = $reflection->getProperty('input');
        $inputProperty->setAccessible(true);
        $namespaceProperty = $reflection->getProperty('commandNamespace');
        $namespaceProperty->setAccessible(true);

        $this->assertEquals($input, $inputProperty->getValue($commandLocator));
        $this->assertEquals($namespace, $namespaceProperty->getValue($commandLocator));
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldParseCommandInput()
    {
        $input = 'some-command arg1 arg2';
        $namespace = 'TestFixture\\';
        $commandLocator = new CommandLocator($input, $namespace);

        $reflection = new ReflectionClass($commandLocator);
        $parseInputMethod = $reflection->getMethod('parseInput');
        $parseInputMethod->setAccessible(true);

        $this->assertEquals([
            'some-command',
            'arg1',
            'arg2'
        ], $parseInputMethod->invoke($commandLocator));
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetCommandClass()
    {
        $input = 'some-command arg1 arg2';
        $namespace = 'TestFixture\\';
        $commandLocator = new CommandLocator($input, $namespace);

        $reflection = new ReflectionClass($commandLocator);
        $parseInputMethod = $reflection->getMethod('getCommandClass');
        $parseInputMethod->setAccessible(true);

        $this->assertEquals('TestFixture\\SomeCommand', $parseInputMethod->invokeArgs($commandLocator, ['some-command']));
    }

    /**
     * @throws ReflectionException
     * @throws CommandException
     */
    public function testShouldInvokeCommandLocator()
    {
        $input = 'test-command arg1 arg2';
        $namespace = 'fixture\\';
        $commandLocator = new CommandLocator($input, $namespace);
        $command = $commandLocator->__invoke();

        $this->assertInstanceOf(Command::class, $command);
        $reflection = new ReflectionClass($command);
        $parameters = $reflection->getProperty('parameters');
        $parameters->setAccessible(true);
        $this->assertEquals([
            'arg1',
            'arg2'
        ], $parameters->getValue($command));
    }

    /**
     * @throws CommandException
     */
    public function testShouldThrowExceptionWhenCommandClassNotExists()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Command \'some-command\' not found. Should be in class \'TestFixture\SomeCommand\'');

        $commandLocator = new CommandLocator('some-command arg1 arg2', 'TestFixture\\');
        $commandLocator->__invoke();
    }
}