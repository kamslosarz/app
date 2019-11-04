<?php

namespace App\Factory;

use App\ApplicationException;
use Container\Process\Process;
use Container\Process\ProcessContext;
use FlashMessenger\FlashMessenger;
use FlashMessenger\FlashMessengerExtension;
use Request\Request;
use View\View;
use View\ViewExtension\Extension\IncludeExtension;
use View\ViewExtension\ExtensionEventManager;

class ViewFactory extends Process
{
    /**
     * @param ProcessContext $processContext
     * @return View
     * @throws ApplicationException
     */
    public function & __invoke(ProcessContext $processContext)
    {
        $this->validate($processContext);

        $extensionEventManager = new ExtensionEventManager();
        $view = new View($this->parameters['resources'], $extensionEventManager);
        $view->addExtension(new IncludeExtension($view));

        /** @var Request $request */
        $request = $processContext->get('request');
        $flashMessenger = new FlashMessenger($request);
        $view->addExtension(new FlashMessengerExtension($flashMessenger));

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
}