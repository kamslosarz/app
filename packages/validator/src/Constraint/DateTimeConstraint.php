<?php

namespace Validator\Constraint;

use DateTime;
use Exception;

class DateTimeConstraint extends Constraint
{
    public function isValid(): bool
    {
        try {
            $format = $this->options->get('format', 'Y-m-d H:i:s');
            $dateTime = DateTime::createFromFormat($format, $this->value);

            if (!$dateTime || $dateTime->format($format) !== $this->value) {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public function getError(): ?string
    {
        return sprintf('Invalid date %s. Correct format is %s', $this->value, $this->options->get('format', 'Y-m-d H:i:s'));
    }
}