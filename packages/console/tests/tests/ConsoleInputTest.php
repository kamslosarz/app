<?php


namespace tests;

use Console\ConsoleInput;
use PHPUnit\Framework\TestCase;

class ConsoleInputTest extends TestCase
{
    public function testShouldConstructConsoleInput()
    {
        $input = [
            'arg1',
            'arg2',
            'arg3'
        ];

        $consoleInput = new ConsoleInput($input);
        $this->assertInstanceOf(ConsoleInput::class, $consoleInput);
    }

    public function testShouldGetConsoleInputCommandAliasSuccess()
    {
        $input = [
            'file.php',
            'arg1',
            'arg2'
        ];

        $consoleInput = new ConsoleInput($input);
        $this->assertEquals('arg1', $consoleInput->getAlias());
    }

    public function testShouldGetConsoleInputCommandAliasFiledWhenArgumentNotPassed()
    {
        $input = [
            'file.php'
        ];
        $consoleInput = new ConsoleInput($input);

        $alias = $consoleInput->getAlias();
        $this->assertNull($alias);
    }

    public function testShouldGetConsoleInputParametersSuccess()
    {
        $commonParameters = ['file.php', 'command'];
        $expectedParameters = [
            'arg1',
            'arg2',
            'arg3',
            'arg4',
            'arg5',
            'arg6',
            'arg7',
        ];

        $consoleInput = new ConsoleInput(array_merge($commonParameters, $expectedParameters));
        $consoleInput->getParameters();
        $parameters = $consoleInput->getParameters();

        $this->assertEquals($expectedParameters, $parameters);
    }

    public function testShouldGetConsoleInputParametersFailed()
    {
        $commonParameters = ['file.php', 'command'];
        $expectedParameters = [];

        $consoleInput = new ConsoleInput(array_merge($commonParameters, $expectedParameters));
        $consoleInput->getParameters();
        $parameters = $consoleInput->getParameters();

        $this->assertEquals($expectedParameters, $parameters);
    }


}