<?php


namespace Validator\Constraint;

class NumberConstraint extends Constraint
{
    private int $max;
    private int $min;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->max = $this->options->get('max', null);
        $this->min = $this->options->get('min', null);
    }

    public function isValid(): bool
    {
        if(!is_numeric($this->value))
        {
            return false;
        }
        if(!is_null($this->max) && $this->value > $this->max)
        {
            return false;
        }
        if(!is_null($this->min) && $this->value < $this->min)
        {
            return false;
        }

        return true;
    }

    public function getError(): string
    {
        return sprintf('Value is not in range. Got %d, expected %d..%d', $this->value, $this->min, $this->max);
    }
}