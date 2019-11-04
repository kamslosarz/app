<?php

namespace Form\FormView;

trait AttributesAsString
{
    protected $attributes = [];

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