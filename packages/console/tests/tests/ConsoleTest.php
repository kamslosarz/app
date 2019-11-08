<?php

namespace tests;

use Collection\Collection;
use Console\Command\Command;
use Console\Console;
use Console\ConsoleException;
use Mockery;
use PHPUnit\Framework\TestCase;

class ConsoleTest extends TestCase
{
    public function testShouldConstructConsole()
    {
        $console = new Console();

        $this->assertInstanceOf(Console::class, $console);
    }

    public function testShouldExecuteConsoleCommandSuccess()
    {
        $expectedOutput = 'command results';
        $commandMock = Mockery::mock(Command::class, [
            Mockery::mock(Collection::class)
        ])
            ->shouldReceive('validate')
            ->once()
            ->getMock()
            ->shouldReceive('execute')
            ->once()
            ->getMock()
            ->shouldReceive('getResults')
            ->andReturn($expectedOutput)
            ->once()
            ->getMock();

        $console = new Console();
        $results = $console->execute($commandMock);

        $this->assertEquals($expectedOutput, $results);
    }

    public function testShouldExecuteConsoleCommandFailedOnValidationException()
    {
        $exceptionMessage = 'exceptionMessage';
        $exceptionMock = Mockery::mock(ConsoleException::class, [
            $exceptionMessage
        ]);
        $this->expectException(ConsoleException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $commandMock = Mockery::mock(Command::class, [
            Mockery::mock(Collection::class)
        ])
            ->shouldReceive('validate')
            ->andThrow($exceptionMock)
            ->getMock();

        $console = new Console();
        $results = $console->execute($commandMock);

        $this->assertNull($results);
    }

    public function testShouldExecuteConsoleCommandFailedOnExecutionException()
    {
        $exceptionMessage = 'exceptionMessage';
        $exceptionMock = Mockery::mock(ConsoleException::class, [
            $exceptionMessage
        ]);
        $this->expectException(ConsoleException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $commandMock = Mockery::mock(Command::class, [
            Mockery::mock(Collection::class)
        ])
            ->shouldReceive('validate')
            ->getMock()
            ->shouldReceive('execute')
            ->andThrow($exceptionMock)
            ->getMock();

        $console = new Console();
        $results = $console->execute($commandMock);

        $this->assertNull($results);
    }
}