<?php

namespace Validator\Constraint;

use Collection\Collection;

abstract class Constraint
{
    protected string $value = '';
    protected Collection $options;

    public function __construct(array $options = [])
    {
        $this->options = new Collection($options);
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    abstract public function isValid(): bool ;

    abstract public function getError(): ?string;
}