<?php

namespace Container;

use App\ApplicationException;
use Collection\Collection;
use Container\Process\Process;
use Container\Process\ProcessContext;
use Container\Process\ProcessFactory;
use Factory\FactoryException;
use Throwable;

class Container
{
    /**
     * @var array
     */
    protected $appProcessContext;
    protected $errorProcessContext;

    /**
     * Container constructor.
     * @param array $dependencyTree
     * @throws ApplicationException
     */
    public function __construct(array $dependencyTree)
    {
        if(!isset($dependencyTree['appProcessContext']) || !isset($dependencyTree['errorProcessContext']))
        {
            throw new ApplicationException('appProcessContext and errorProcessContext is required');
        }
        $this->appProcessContext = $dependencyTree['appProcessContext'];
        $this->errorProcessContext = $dependencyTree['errorProcessContext'];
    }

    public function __invoke(): ProcessContext
    {
        $processContext = new ProcessContext();
        if(!$this->processProcessContext($this->appProcessContext, $processContext))
        {
            $this->processProcessContext($this->errorProcessContext, $processContext);
        }

        return $processContext;
    }

    private function processProcessContext(Collection $processContextElements, ProcessContext &$processContext): bool
    {
        foreach($processContextElements as $name => $dependencyParameters)
        {
            try
            {
                $process = $this->factoryProcess($dependencyParameters);
                $processContext->set($name, $process($processContext));
            }
            catch(Throwable $exception)
            {
                $processContext->set($name, $exception);
                $processContext->set('containerException', $exception);

                return false;
            }
        }

        return true;
    }

    /**
     * @param $dependencyItem
     * @return Process
     * @throws FactoryException
     */
    protected function factoryProcess($dependencyItem): Process
    {
        return ProcessFactory::getInstance($dependencyItem);
    }
}