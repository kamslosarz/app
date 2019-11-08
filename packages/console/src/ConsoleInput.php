<?php

namespace Console;

class ConsoleInput
{
    private array $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function getAlias(): ?string
    {
        $alias = isset($this->input[1]) ? $this->input[1] : null;

        return $alias;
    }

    public function getParameters(): array
    {
        if(sizeof($this->input) > 1)
        {
            return array_slice($this->input, 2, sizeof($this->input, true));
        }

        return [];
    }
}