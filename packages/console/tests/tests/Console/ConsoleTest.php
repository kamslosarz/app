<?php

use Console\Command\Command;
use Console\Command\CommandException;
use Console\Command\CommandLocator;
use Console\Console;
use PHPUnit\Framework\TestCase;


class ConsoleTest extends TestCase
{
    /**
     * @throws CommandException
     * @doesNotPerformAssertions
     */
    public function testShouldInvokeCommand()
    {
        $commandMock = Mockery::mock(Command::class)
            ->shouldReceive('__invoke')
            ->getMock();

        $commandLocatorMock = Mockery::mock(CommandLocator::class)
            ->shouldReceive('__invoke')
            ->andReturn($commandMock)
            ->getMock();

        /** @var Console $console */
        $console = Mockery::mock(Console::class, [
            [
                'commandNamespace' => 'Console\\Command\\'
            ],
            'some-command arg1 arg2'
        ])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getCommandLocator')
            ->once()
            ->withNoArgs()
            ->andReturn($commandLocatorMock)
            ->getMock();

        $console->__invoke();

        $commandMock->shouldHaveReceived('__invoke')->once();
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetCommandLocator()
    {
        $console = new Console([
            'commandNamespace' => 'Console\\Command\\'
        ], 'command');

        $reflection = new ReflectionClass($console);
        $method = $reflection->getMethod('getCommandLocator');
        $method->setAccessible(true);
        $commandLocator = $method->invoke($console);

        $this->assertInstanceOf(CommandLocator::class, $commandLocator);
    }
}