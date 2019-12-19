<?php

namespace App\Validator;

use Validator\Constraint\Constraint;
use Validator\Validator;

class RequestValidator extends Validator
{
    /**
     * @param array $data
     * @return bool
     */
    public function validateRequestData(array $data): bool
    {
        /** @var Constraint $constraint */
        foreach ($this->constraints as $name => $constraint) {
            $this->validate($name, $data[$name] ?? null);
        }

        return empty($this->errors);
    }
}