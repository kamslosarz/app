<?php

namespace Collection;

use ArrayObject;

abstract class ArrayAccessible extends ArrayObject
{
    public function __construct($input = array())
    {
        parent::__construct($input, 0, 'ArrayIterator');
    }

    public function __toArray(): array
    {
        return $this->getArrayCopy();
    }
}