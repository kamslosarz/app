<?php

namespace Console;

use Console\Command\CommandLocator;

class Console
{
    private $input = '';
    private $config = [];

    public function __construct(array $config, string $input)
    {
        $this->config = $config;
        $this->input = $input;
    }

    /**
     * @return bool
     * @throws Command\CommandException
     */
    public function __invoke()
    {
        return $this->getCommandLocator()()();
    }

    protected function getCommandLocator(): CommandLocator
    {
        return new CommandLocator($this->input, $this->config['commandNamespace']);
    }
}