<?php

namespace App\Controller\AbstractController;

use App\Controller\RequestValidator;
use Factory\FactoryException;
use FlashMessenger\FlashMessenger;
use Validator\ConstraintBuilder\ConstraintBuilder;

abstract class AppController extends Controller
{
    protected array $errors;

    protected function handleFormErrors(array $errors): void
    {
        $flashMessenger = $this->getFlashMessenger();
        foreach ($errors as $fieldName => $fieldErrors) {
            $flashMessenger->add(sprintf('%s - %s', $fieldName, implode(', ', $fieldErrors)),
                FlashMessenger::TYPE_ERROR);
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
                sprintf('Location: %s', $url),
            ])
            ->set('responseCode', $code);
    }

    /**
     * @param array $array
     * @return string
     */
    protected function json(array $array): string
    {
        return json_encode($array);
    }

    protected function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $data
     * @param ConstraintBuilder $constraintBuilder
     * @return bool
     * @throws FactoryException
     */
    protected function validate(array $data, ConstraintBuilder $constraintBuilder): bool
    {
        $requestValidator = new \App\Validator\RequestValidator($constraintBuilder);
        if (!$requestValidator->validateRequestData($data)) {
            $this->errors = $requestValidator->getErrors();

            return false;
        }

        return true;
    }
}
