<?php

namespace App;

use Container\Container;
use Container\Process\ProcessContext;
use Throwable;

class App
{
    private ProcessContext $processContext;

    /**
     * @param array $dependencyTree
     */
    public function __invoke(array $dependencyTree): void
    {
        try
        {
            $container = new Container($dependencyTree);
            $this->processContext = $container();

            if(!$this->processContext->has('applicationError'))
            {
                if(!$this->processContext->has('containerException'))
                {
                    $this->processContext->get('response')();
                }
                else
                {
                    $this->handleError($this->processContext->get('containerException'));
                }
            }
            else
            {
                $this->handleError($this->processContext->get('applicationError'));
            }
        }
        catch(ApplicationException $throwable)
        {
            $this->handleError($throwable);
        }
    }

    private function handleError(Throwable $error): void
    {
        if(defined('DEBUG_MODE') && DEBUG_MODE === true)
        {
            echo sprintf("%s in %s:%s </br></br> <pre>%s</pre>", $error->getMessage(), $error->getFile(), $error->getLine(), $error->getTraceAsString());
        }
    }

    public function getProcessContext(): ProcessContext
    {
        return $this->processContext;
    }
}