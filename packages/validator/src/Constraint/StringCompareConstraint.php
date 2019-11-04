<?php


namespace Validator\Constraint;

class StringCompareConstraint extends Constraint
{
    public function isValid()
    {
        $expected = $this->options->get('expected');
        $compareStatement = $this->options->get('compareStatement', null);
        if($compareStatement)
        {
            return $compareStatement($expected, $this->value);
        }
        else
        {
            return $expected === $this->value;
        }
    }

    public function getError(): string
    {
        return sprintf('String \'%s\' is not match \'%s\'', $this->value, $this->options->get('expected'));
    }
}