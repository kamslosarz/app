<?php

namespace App\Form\Login;


use EventManager\Event\Context;
use Form\Form;
use Form\FormBuilder\FormBuilder;
use Form\FormBuilder\FormBuilderException;
use Form\Handler\FormHandler;
use Validator\Constraint\RegexConstraint;
use Validator\ConstraintBuilder\ConstraintBuilder;
use Validator\Validator;

class LoginForm extends Form
{
    public function __construct(Context $context, FormBuilder $formBuilder, Validator $validator)
    {
        parent::__construct($context, $formBuilder, $validator);

        $this->setAttributes([
            'name' => 'login-form',
            'method' => 'post'
        ]);
    }

    /**
     * @param FormBuilder $formBuilder
     * @param Context $context
     * @throws FormBuilderException
     */
    protected function buildFields(FormBuilder &$formBuilder, Context $context): void
    {
        $formBuilder->addInput([
            'name' => 'login',
        ])->addInput([
            'name' => 'password',
        ])->addButton([
            'name' => 'submit',
            'value' => 'save',
            'id' => 'login-button'
        ], [
            'label' => 'save'
        ]);
    }

    /**
     * @param ConstraintBuilder $constraintBuilder
     * @param Context $context
     */
    protected function buildConstraints(ConstraintBuilder &$constraintBuilder, Context $context): void
    {
        $constraintBuilder->addConstraint('login', RegexConstraint::class, [
            'regex' => '/[a-zA-Z0-9-.]{4,16}/'
        ]);
    }

    /**
     * @return FormHandler
     */
    protected function getHandler(): FormHandler
    {
        return new LoginFormHandler();
    }
}