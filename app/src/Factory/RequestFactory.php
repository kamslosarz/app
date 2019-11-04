<?php


namespace App\Factory;

use Container\Process\Process;
use Container\Process\ProcessContext;
use Request\Request;

class RequestFactory extends Process
{
    public function & __invoke(ProcessContext $processContext): Request
    {
        $request = new Request();

        return $request;
    }
}