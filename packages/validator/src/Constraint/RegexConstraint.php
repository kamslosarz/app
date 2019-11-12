<?php

namespace Validator\Constraint;

use Closure;

class RegexConstraint extends Constraint
{
    private ?string $regex;
    private ?bool $matchAll;
    private ?Closure $callback;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->regex = $this->options->get('regex', null);
        $this->matchAll = $this->options->get('matchAll', false);
        $this->callback = $this->options->get('callback', null);
    }

    public function isValid()
    {
        $matches = [];
        if($this->matchAll)
        {
            $matched = preg_match_all($this->regex, $this->value, $matches);
        }
        else
        {
            $matched = preg_match($this->regex, $this->value, $matches);
        }
        if(is_callable($this->callback))
        {
            return ($this->callback)($matches);
        }

        return $matched > 0;
    }

    public function getError(): string
    {
        return sprintf('Regex \'%s\' does not match string \'%s\'', $this->regex, $this->value);
    }
}