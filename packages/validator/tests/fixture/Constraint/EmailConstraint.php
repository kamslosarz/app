<?php

namespace fixture\Constraint;

use Validator\Constraint\Constraint;

class EmailConstraint extends Constraint
{
    public function isValid(): bool
    {
        return filter_var($this->value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getError(): string
    {
        return sprintf($this->options['message'], $this->value);
    }
}