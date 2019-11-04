<?php

namespace fixture;

use EventManager\Event\Context;
use Form\Form;
use Form\FormBuilder\FormBuilder;
use Form\FormBuilder\FormBuilderException;
use Form\Handler\FormHandler;
use Mockery;
use Validator\Constraint\CharacterLengthConstraint;
use Validator\Constraint\OptionListConstraint;
use Validator\Constraint\RegexConstraint;
use Validator\ConstraintBuilder\ConstraintBuilder;

class ExampleForm extends Form
{
    /**
     * @param FormBuilder $formBuilder
     * @param Context $context
     * @throws FormBuilderException
     */
    protected function buildFields(FormBuilder &$formBuilder, Context $context): void
    {
        $formBuilder
            ->addInput([
                'name' => 'login',
                'class' => 'some-class'
            ])
            ->addInput([
                'name' => 'password',
                'class' => 'some-class'
            ])
            ->addSelect([
                'name' => 'select-name',
                'class' => 'some-class'
            ], [
                'options' => [
                    'value' => 'name',
                    'value2' => 'name2'
                ]
            ])
            ->addTextarea([
                'name' => 'textarea-name',
                'class' => 'some-textarea-class'
            ]);
    }


    /**
     * @param ConstraintBuilder $constraintBuilder
     * @param Context $context
     */
    protected function buildConstraints(ConstraintBuilder &$constraintBuilder, Context $context): void
    {
        $constraintBuilder->addConstraint('login', RegexConstraint::class, [
            'regexp' => '^[a-zA-Z0-9]{6,32}$'
        ])->addConstraint('password', RegexConstraint::class, [
            'regexp' => '^[a-zA-Z0-9]{6,32}$'
        ])->addConstraint('select-name', OptionListConstraint::class, [
            'options' => [
                'value' => 'name',
                'value2' => 'name2'
            ]
        ])->addConstraint('textarea-name', CharacterLengthConstraint::class, [
            'min' => 1,
            'max' => 5
        ]);
    }

    /**
     * @return FormHandler
     */
    protected function getHandler(): FormHandler
    {
        return Mockery::mock(FormHandler::class);
    }
}