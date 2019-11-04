<?php

namespace Collection;

use Serializable;

class Collection extends ArrayAccessible implements Serializable
{
    public function __construct(array $array = [])
    {
        parent::__construct($array);
    }

    public function add(string $key, $value): self
    {
        if($this->offsetExists($key))
        {
            $items = $this->offsetGet($key);
        }
        else
        {
            $items = [];
        }
        $items[] = $value;
        $this->offsetSet($key, $items);

        return $this;
    }

    public function remove(string $key): self
    {
        $this->offsetUnset($key);

        return $this;
    }

    public function get(string $key, $default = null)
    {
        return $this->offsetExists($key) ? $this->offsetGet($key) : $default;
    }

    public function set(string $key, $value): self
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    public function has($key): bool
    {
        return $this->offsetExists($key);
    }

    public function serialize()
    {
        return serialize($this);
    }

    public function unserialize($serialized)
    {
        return unserialize($serialized);
    }
}