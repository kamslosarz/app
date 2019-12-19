<?php

namespace Validator\ConstraintBuilder;

use Builder\Builder;

class ConstraintBuilder extends Builder
{
    public function addConstraint(string $name, string $constraintClass, array $options = []): self
    {
        $this->add($name, [$constraintClass, [$options]]);

        return $this;
    }
}