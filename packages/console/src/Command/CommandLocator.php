<?php

namespace Console\Command;

class CommandLocator
{
    private string $input;
    private string $commandNamespace;

    public function __construct(string $input, string $commandNamespace)
    {
        $this->input = $input;
        $this->commandNamespace = $commandNamespace;
    }

    /**
     * @return Command
     * @throws CommandException
     */
    public function __invoke(): Command
    {
        $inputParameters = $this->parseInput();
        $command = $inputParameters[0];
        $commandClass = $this->getCommandClass($command);
        if(!$commandClass || !class_exists($commandClass))
        {
            throw new CommandException(sprintf('Command \'%s\' not found. Should be in class \'%s\'', $command, $commandClass));
        }

        return $this->getCommandInstance($commandClass, array_slice($inputParameters, 1));
    }

    /**
     * @return array
     */
    protected function parseInput(): array
    {
        return explode(' ', $this->input);
    }

    /**
     * @param string $command
     * @return string|null
     */
    protected function getCommandClass(string $command): ?string
    {
        $array = explode('-', $command);
        array_walk($array, function (&$item) {
            $item = ucfirst(strtolower($item));
        });

        return sprintf('%s%s', $this->commandNamespace, implode('', $array));
    }

    /**
     * @param string|null $commandClass
     * @param array $inputParameters
     * @return Command
     */
    private function getCommandInstance(?string $commandClass, array $inputParameters): Command
    {
        return CommandFactory::getInstance($commandClass, $inputParameters);
    }
}