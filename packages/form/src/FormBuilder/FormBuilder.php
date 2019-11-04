<?php

namespace Form\FormBuilder;

use Builder\Builder;
use Form\Field\Button;
use Form\Field\Fieldset;
use Form\Field\Input;
use Form\Field\Select;
use Form\Field\Textarea;

class FormBuilder extends Builder
{
    /**
     * @param string $elementClassname
     * @param array $attributes
     * @param array $options
     * @return $this
     * @throws FormBuilderException
     */
    protected function addField(string $elementClassname, array $attributes, array $options): self
    {
        if(!isset($attributes['name']))
        {
            throw new FormBuilderException(sprintf('Field attribute \'name\' cannot be empty. Given: \'%s\'', print_r($attributes, true)));
        }

        return $this->add('fields', [$elementClassname, [$attributes, $options]]);
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return $this
     * @throws FormBuilderException
     */
    public function addTextarea(array $attributes, array $options = []): self
    {
        return $this->addField(Textarea::class, $attributes, $options);
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return $this
     * @throws FormBuilderException
     */
    public function addSelect(array $attributes, array $options = []): self
    {
        return $this->addField(Select::class, $attributes, $options);
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return $this
     * @throws FormBuilderException
     */
    public function addInput(array $attributes, array $options = []): self
    {
        return $this->addField(Input::class, $attributes, $options);
    }

    /**
     * @param array $attributes
     * @param array $options
     * @return $this
     * @throws FormBuilderException
     */
    public function addButton(array $attributes, array $options = []): self
    {
        return $this->addField(Button::class, $attributes, $options);
    }

    /**
     * @param array $attributes
     * @param string $legend
     * @return $this
     * @throws FormBuilderException
     */
    public function addFieldset(array $attributes, string $legend)
    {
        return $this->addField(Fieldset::class, $attributes, ['legend' => $legend]);
    }

    public function build(): array
    {
        return parent::build()['fields'];
    }
}