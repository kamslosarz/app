<?php

namespace EventManager\Event;

class Event
{
    private string $name;
    private array $results = [];
    private Context $context;

    /**
     * Event constructor.
     * @param string $eventName
     * @param Context $context
     */
    public function __construct(string $eventName, Context $context)
    {
        $this->name = $eventName;
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $results
     * @return $this
     */
    public function addResults($results): self
    {
        $this->results[] = $results;

        return $this;
    }

    /**
     * @return string
     */
    public function getStringResults(): string
    {
        return implode('', $this->results);
    }

    /**
     * @return mixed
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }
}