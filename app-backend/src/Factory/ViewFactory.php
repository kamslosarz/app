<?php

namespace App\Factory;

use App\ApplicationException;
use Closure;
use Container\Process\Process;
use Container\Process\ProcessContext;
use Factory\Factory;
use Factory\FactoryException;
use Request\Request;
use View\View;
use View\ViewExtension\ExtensionEventManager;

class ViewFactory extends Process
{
    /**
     * @param ProcessContext $processContext
     * @return View
     * @throws ApplicationException
     * @throws FactoryException
     */
    public function & __invoke(ProcessContext $processContext)
    {
        $this->validate($processContext);

        $extensionEventManager = new ExtensionEventManager();
        $view = new View($this->parameters['resources'], $extensionEventManager);
        $this->addViewExtensions($view, $processContext);

        return $view;
    }

    /**
     * @param ProcessContext $processContext
     * @throws ApplicationException
     */
    private function validate(ProcessContext $processContext)
    {
        $request = $processContext->get('request');
        if(!$request instanceof Request)
        {
            throw new ApplicationException('Request not exists');
        }
    }

    /**
     * @param View $view
     * @param ProcessContext $processContext
     * @throws FactoryException
     */
    private function addViewExtensions(View $view, ProcessContext $processContext): void
    {
        foreach($this->getViewExtensions() as $extensionClassName => $parameters)
        {
            if(is_callable($parameters))
            {
                /**@var Closure $parameters */
                $parameters = $parameters($view, $processContext);
            }
            $extension = Factory::getInstance([$extensionClassName, $parameters]);
            $view->addExtension($extension);
        }
    }

    private function getViewExtensions(): array
    {
        return !empty($this->parameters['viewExtensions']) ? $this->parameters['viewExtensions'] : [];
    }
}