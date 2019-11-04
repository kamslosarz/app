<?php

namespace fixture;

use Console\Command\Command;

class TestCommand extends Command
{
    public function __invoke(): bool
    {
        return true;
    }
}