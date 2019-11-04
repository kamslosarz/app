<?php

namespace Form\Field;

use Collection\Collection;

class Fields extends Collection
{
    public function has($name): bool
    {
        /** @var FormField $field */
        foreach($this->getIterator() as $field)
        {
            if($field->getName() === $name)
            {
                return true;
            }
        }

        return false;
    }

    public function get(string $key, $default = null): ?FormField
    {
        /** @var FormField $field */
        foreach($this->getIterator() as $field)
        {
            if($field->getName() === $key)
            {
                return $field;
            }
        }

        return null;
    }
}
