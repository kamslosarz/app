<?php

namespace Builder;

use Builder\ElementFactory\ElementFactory;
use Factory\FactoryException;

class Builder
{
    protected array $elements = [];

    protected function add(string $name, array $parameters): self
    {
        $this->elements[$name][] = $parameters;

        return $this;
    }

    /**
     * @return array
     * @throws FactoryException
     */
    public function build(): array
    {
        $build = [];
        foreach($this->elements as $name => $elements)
        {
            foreach($elements as $parameters)
            {
                $build[$name][] = ElementFactory::getInstance($parameters);
            }
        }

        return $build;
    }
}