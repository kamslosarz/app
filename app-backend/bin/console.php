<?php

require '../vendor/autoload.php';

use Console\Command\CommandFactory;
use Console\Command\CommandRegister;
use Console\Console;
use Console\ConsoleInput;

$input = $argv;
try
{
    $config = include APP_DIR . '/config/console.php';
    $commandRegister = new CommandRegister($config['commands']);
    $commandRegister->register();

    $consoleInput = new ConsoleInput($input);
    $command = CommandFactory::getInstance($consoleInput, $commandRegister);

    $console = new Console();
    $console->execute($command);
}
catch(Throwable $throwable)
{
    echo sprintf('Execution failed: command: [ %s ] error: %s details: %s' . PHP_EOL, implode(' ', $input), $throwable->getMessage(), PHP_EOL . $throwable->getTraceAsString());
}