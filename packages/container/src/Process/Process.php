<?php

namespace Container\Process;

abstract class Process
{
    /**
     * @var array
     */
    protected $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    abstract public function & __invoke(ProcessContext $processContext);
}