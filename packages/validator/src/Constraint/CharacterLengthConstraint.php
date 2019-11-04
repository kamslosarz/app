<?php


namespace Validator\Constraint;

class CharacterLengthConstraint extends Constraint
{
    private $max;
    private $min;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->max = $this->options->get('max', 200);
        $this->min = $this->options->get('min', 1);
    }

    public function isValid()
    {
        $length = strlen($this->value);

        return $length >= $this->min && $length <= $this->max;
    }

    public function getError(): string
    {
        return sprintf('Value length is not in range. Got %d, expected %d..%d', strlen($this->value), $this->min, $this->max);
    }
}