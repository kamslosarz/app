<?php

namespace Form\Field;

abstract class FormField extends FormElement
{
    public function getName(): string
    {
        return $this->attributes->get('name', '');
    }

    public function setValue($value): self
    {
        $this->attributes->set('value', $value);

        return $this;
    }
}