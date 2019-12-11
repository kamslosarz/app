<?php

namespace App\Controller\AbstractController;

use FlashMessenger\FlashMessenger;

abstract class AppController extends Controller
{
    protected function handleFormErrors(array $errors): void
    {
        $flashMessenger = $this->getFlashMessenger();
        foreach($errors as $fieldName => $fieldErrors)
        {
            $flashMessenger->add(sprintf('%s - %s', $fieldName, implode(', ', $fieldErrors)), FlashMessenger::TYPE_ERROR);
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
}