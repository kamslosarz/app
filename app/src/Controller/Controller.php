<?php

namespace App\Controller;

use EventManager\Event\Context;
use EventManager\Listener\EventListenerInvokable;
use Factory\FactoryException;
use FlashMessenger\FlashMessenger;
use Form\Form;
use Form\FormFactory;
use Request\Request;
use ServiceContainer\ServiceContainer;
use View\View;

abstract class Controller implements EventListenerInvokable
{
    protected Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @return ServiceContainer
     */
    protected function getServiceContainer(): ServiceContainer
    {
        return $this->context->get('serviceContainer');
    }

    /**
     * @return View
     */
    protected function getView(): View
    {
        return $this->context->get('view');
    }

    /**
     * @return Request
     */
    protected function getRequest(): Request
    {
        return $this->context->get('request');
    }

    /**
     * @param string $classname
     * @return Form
     */
    protected function getForm(string $classname): Form
    {
        try
        {
            return FormFactory::getInstance($classname, $this->context);
        }
        catch(FactoryException $factoryException)
        {
            return null;
        }
    }

    /**
     * @param string $url
     * @param int $code
     */
    protected function redirect(string $url, int $code = 302)
    {
        $this->context
            ->set('responseHeaders', [
                sprintf('Location: %s', $url)
            ])
            ->set('responseCode', $code);
    }

    /**
     * @return FlashMessenger
     */
    protected function getFlashMessenger(): FlashMessenger
    {
        $request = $this->getRequest();
        return new FlashMessenger($request);
    }

    protected function handleFormErrors(array $errors): void
    {
        $flashMessenger = $this->getFlashMessenger();
        foreach($errors as $fieldName => $fieldErrors)
        {
            $flashMessenger->add(sprintf('%s - %s', $fieldName, implode(', ', $fieldErrors)), FlashMessenger::TYPE_ERROR);
        }
    }
}