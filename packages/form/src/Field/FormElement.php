<?php

namespace Form\Field;

use Collection\Collection;

abstract class FormElement
{
    /**
     * @var Collection
     */
    protected $attributes;
    /**
     * @var Collection
     */
    protected $options;

    public function __construct(array $attributes, array $options)
    {
        $this->attributes = new Collection($attributes);
        $this->options = new Collection($options);
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function getOptions(): Collection
    {
        return $this->options;
    }
}