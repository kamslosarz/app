<?php


namespace tests\Command;

use Collection\Collection;
use Console\Command\CommandFactory;
use Console\Command\CommandRegister;
use Console\ConsoleException;
use Console\ConsoleInput;
use fixture\ExampleCommand;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class CommandFactoryTest extends TestCase
{
    /**
     * @throws ConsoleException
     * @throws ReflectionException
     */
    public function testShouldConstructCommandSuccess()
    {
        $alias = 'alias:sub';
        $parameters = ['a1', 'a2'];
        $consoleInputMock = Mockery::mock(ConsoleInput::class)
            ->shouldReceive('getAlias')
            ->andReturn($alias)
            ->once()
            ->getMock()->shouldReceive('getParameters')
            ->once()
            ->andReturn($parameters)
            ->getMock();

        $commandRegisterMock = Mockery::mock(CommandRegister::class)
            ->shouldReceive('hasAlias')
            ->with($alias)
            ->once()
            ->andReturnTrue()
            ->getMock()
            ->shouldReceive('getByAlias')
            ->once()
            ->andReturn(ExampleCommand::class)
            ->getMock();

        $command = CommandFactory::getInstance($consoleInputMock, $commandRegisterMock);
        $commandParameters = new ReflectionProperty($command, 'parameters');
        $commandParameters->setAccessible(true);

        $this->assertInstanceOf(ExampleCommand::class, $command);
        $this->assertEquals(new Collection($parameters), $commandParameters->getValue($command));
    }

    /**
     * @throws ConsoleException
     */
    public function testShouldThrowExceptionWhenConstructNotExistingAlias()
    {
        $this->expectException(ConsoleException::class);
        $this->expectExceptionMessage('Command with alias \'alias\' not exists');

        $consoleInputMock = Mockery::mock(ConsoleInput::class)
            ->shouldReceive('getAlias')
            ->andReturn('alias')
            ->once()
            ->getMock();

        $commandRegisterMock = Mockery::mock(CommandRegister::class)
            ->shouldReceive('hasAlias')
            ->andReturnFalse()
            ->once()
            ->getMock();

        CommandFactory::getInstance($consoleInputMock, $commandRegisterMock);
    }
}