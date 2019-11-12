<?php

namespace Form\Handler;

abstract class FormHandler
{
    private array $errors = [];

    abstract public function validate(array $fields): void;

    abstract public function handle(array $data): void;

    protected function addError(string $name, string $error): void
    {
        $this->errors[$name][] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}