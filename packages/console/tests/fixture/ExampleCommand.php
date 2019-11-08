<?php


namespace fixture;

use Console\Command\Command;

class ExampleCommand extends Command
{
    public function validate(): void
    {
        // TODO: Implement validate() method.
    }

    public function execute(): void
    {
        // TODO: Implement execute() method.
    }

    static function getAlias(): string
    {
        return 'alias:sub';
    }
}