<?php

namespace fixture\Process;

use Container\Process\Process;
use Container\Process\ProcessContext;

class ExampleProcess extends Process
{
    /**
     * @param ProcessContext $processContext
     * @return string
     */
    public function & __invoke(ProcessContext $processContext)
    {
        $process = 'processReference';

        return $process;
    }
}