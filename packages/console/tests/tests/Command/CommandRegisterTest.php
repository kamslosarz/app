<?php

namespace tests\Command;

use Console\Command\Command;
use Console\Command\CommandRegister;
use fixture\ExampleCommand;
use Mockery;
use PHPUnit\Framework\Error\Notice;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class CommandRegisterTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructCommandRegister()
    {
        $commands = [
            ExampleCommand::class
        ];
        $commandRegister = new CommandRegister($commands);

        $commandsProperty = new ReflectionProperty($commandRegister, 'commands');
        $commandsProperty->setAccessible(true);

        $this->assertInstanceOf(CommandRegister::class, $commandRegister);
        $this->assertEquals($commands, $commandsProperty->getValue($commandRegister));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldRegisterCommands()
    {
        $commandMock = Mockery::spy(ExampleCommand::class)
            ->shouldReceive('getAlias')
            ->once()
            ->getMock();

        $commands = [
            $commandMock,
            $commandMock,
        ];

        $commandRegister = new CommandRegister($commands);
        $commandRegister->register();
        $commandMock->shouldHaveReceived('getAlias')->twice();
    }

    public function testShouldCheckIfHasAliasAfterRegisterSuccess()
    {
        $alias = 'command-alias';
        $commandMock = Mockery::mock(Command::class)
            ->shouldReceive('getAlias')
            ->andReturn($alias)
            ->getMock();

        $commands = [
            $commandMock
        ];

        $commandRegister = new CommandRegister($commands);
        $commandRegister->register();
        $result = $commandRegister->hasAlias($alias);

        $this->assertTrue($result);
    }

    public function testShouldCheckIfHasAliasAfterRegisterFailed()
    {
        $alias = 'command-alias';
        $commandMock = Mockery::mock(Command::class)
            ->shouldReceive('getAlias')
            ->andReturn('other-command-alias')
            ->getMock();

        $commands = [
            $commandMock
        ];

        $commandRegister = new CommandRegister($commands);
        $commandRegister->register();
        $result = $commandRegister->hasAlias($alias);

        $this->assertFalse($result);
    }

    public function testShouldCheckIfHasAliasbeforeRegisterFailed()
    {
        $alias = 'command-alias';
        $commandMock = Mockery::mock(Command::class)
            ->shouldReceive('getAlias')
            ->andReturn($alias)
            ->getMock();

        $commands = [
            $commandMock
        ];

        $commandRegister = new CommandRegister($commands);
        $result = $commandRegister->hasAlias($alias);

        $this->assertFalse($result);
    }

    public function testShouldGetCommandByAliasSuccess()
    {
        $alias = 'command-alias';
        $commandMock = Mockery::mock(Command::class)
            ->shouldReceive('getAlias')
            ->andReturn($alias)
            ->getMock();

        $commands = [
            $commandMock
        ];

        $commandRegister = new CommandRegister($commands);
        $commandRegister->register();
        $result = $commandRegister->getByAlias($alias);

        $this->assertEquals($commandMock, $result);
    }

    public function testShouldGetCommandByAliasFailedBeforeRegister()
    {
        $this->expectException(Notice::class);
        $this->expectExceptionMessage('Undefined index: command-alias');

        $alias = 'command-alias';
        $commandMock = Mockery::mock(Command::class)
            ->shouldReceive('getAlias')
            ->andReturn($alias)
            ->getMock();

        $commands = [
            $commandMock
        ];

        $commandRegister = new CommandRegister($commands);
        $result = $commandRegister->getByAlias($alias);

        $this->assertEquals($commandMock, $result);
    }
}