<?php

namespace Console;

use Console\Command\Command;

class Console
{
    protected ?string $error = null;
    protected ?string $output = null;

    public function execute(Command $command): ?string
    {
        $command->validate();
        $command->execute();

        return $command->getResults();
    }
}