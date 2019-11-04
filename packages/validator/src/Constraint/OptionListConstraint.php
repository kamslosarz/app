<?php


namespace Validator\Constraint;

class OptionListConstraint extends Constraint
{
    private $optionsList;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->optionsList = array_keys($this->options->get('options', []));
    }

    public function isValid()
    {
        return in_array($this->value, $this->optionsList);
    }

    public function getError(): string
    {
        return sprintf('Option \'%s\' not in range \%s\'', $this->value, implode(', ', $this->optionsList));
    }
}