<?php

namespace Form\FormView;

use ArrayObject;

trait AttributesAsString
{
    protected ArrayObject $attributes;

    public function getAttributesAsString(array $except = []): string
    {
        $attributesAsString = '';
        foreach($this->attributes as $name => $value)
        {
            if(!in_array($name, $except))
            {
                $attributesAsString .= sprintf(' %s="%s"', $name, $value);
            }
        }

        return $attributesAsString;
    }
}