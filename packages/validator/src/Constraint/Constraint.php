<?php

namespace Validator\Constraint;

use Collection\Collection;

abstract class Constraint
{
    protected $value = '';
    protected $options = [];

    public function __construct(array $options = [])
    {
        $this->options = new Collection($options);
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    abstract public function isValid();

    abstract public function getError(): ?string;
}