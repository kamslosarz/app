<?php

namespace Tests;

class FormExpectation
{
    private array $expectedFieldAdditions = [];
    private array $expectedConstraintAdditions = [];

    public function expectFieldAddition($fieldName, $fieldArgs): self
    {
        $this->expectedFieldAdditions[] = [$fieldName, $fieldArgs];

        return $this;
    }

    public function expectConstraintAddition(string $fieldName, string $constraint, array $parameters): self
    {
        $this->expectedConstraintAdditions[] = [$fieldName, $constraint, $parameters];

        return $this;
    }

    public function getConstraintExpectations(): array
    {
        return $this->expectedConstraintAdditions;
    }

    public function getFieldsExpectations(): array
    {
        return $this->expectedFieldAdditions;
    }
}