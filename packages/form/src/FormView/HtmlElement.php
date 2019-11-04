<?php

namespace Form\FormView;

use Collection\Collection;

class HtmlElement
{
    use AttributesAsString;

    protected $resource;
    protected $options;

    public function __construct(string $resource, Collection $attributes, Collection $options)
    {
        $this->resource = $resource;
        $this->attributes = $attributes;
        $this->options = $options;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getAttribute(string $name, $default = null)
    {
        return $this->attributes->get($name, $default);
    }

    public function hasAttribute(string $name): bool
    {
        return $this->attributes->offsetExists($name);
    }

    public function hasOption(string $name)
    {
        return $this->options->offsetExists($name);
    }
    public function getOption(string $name, $default = null)
    {
        return $this->options->get($name, $default);
    }
}