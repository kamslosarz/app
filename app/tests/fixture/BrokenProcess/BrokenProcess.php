<?php

namespace fixture\BrokenProcess;

use Container\Process\Process;
use Container\Process\ProcessContext;
use Exception;

class BrokenProcess extends Process
{
    /**
     * BrokenProcess constructor.
     * @param array $parameters
     * @throws Exception
     */
    public function __construct(array $parameters)
    {
        parent::__construct($parameters);

        throw new Exception('some error');
    }

    /**
     * @param ProcessContext $processContext
     */
    public function & __invoke(ProcessContext $processContext)
    {
        // TODO: Implement __invoke() method.
    }
}