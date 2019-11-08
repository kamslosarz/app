<?php

namespace Console\Command;

use Collection\Collection;
use Console\ConsoleException;
use Console\ConsoleInput;

abstract class CommandFactory
{
    /**
     * @param ConsoleInput $consoleInput
     * @param CommandRegister $commandRegister
     * @return Command
     * @throws ConsoleException
     */
    public static function getInstance(ConsoleInput $consoleInput, CommandRegister $commandRegister): Command
    {
        $alias = $consoleInput->getAlias();
        if($commandRegister->hasAlias($alias))
        {
            $commandClassname = $commandRegister->getByAlias($consoleInput->getAlias());

            return new $commandClassname(new Collection($consoleInput->getParameters()));
        }

        throw new ConsoleException(sprintf('Command with alias \'%s\' not exists', $alias));
    }
}