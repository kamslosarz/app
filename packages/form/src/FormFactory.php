<?php

namespace Form;

use App\Form\Validator\FormValidator;
use EventManager\Event\Context;
use Factory\FactoryException;
use Form\FormBuilder\FormBuilder;
use Validator\ConstraintBuilder\ConstraintBuilder;

class FormFactory
{
    /**
     * @param string $formClassname
     * @param Context $context
     * @return Form
     * @throws FactoryException
     */
    public static function getInstance(string $formClassname, Context $context): Form
    {
        $formBuilder = new FormBuilder();
        $constraintBuilder = new ConstraintBuilder();
        $validator = new FormValidator($constraintBuilder);

        return new $formClassname($context, $formBuilder, $validator);
    }
}