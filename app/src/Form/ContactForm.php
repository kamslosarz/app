<?php


namespace App\Form;

use App\Form\Handler\ContactFormHandler;
use EventManager\Event\Context;
use Form\Form;
use Form\FormBuilder\FormBuilder;
use Form\FormBuilder\FormBuilderException;
use Form\Handler\FormHandler;
use Validator\ConstraintBuilder\ConstraintBuilder;
use Validator\Validator;

class ContactForm extends Form
{

    public function __construct(Context $context, FormBuilder $formBuilder, Validator $validator)
    {
        parent::__construct($context, $formBuilder, $validator);

        $this->setAttributes([
            'name' => 'contact-form',
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
            'name' => 'title'
        ])->addTextarea([
            'name' => 'message'
        ])->addButton([
            'name' => 'submit'
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
        // TODO: Implement buildConstraints() method.
    }

    /**
     * @return FormHandler
     */
    protected function getHandler(): FormHandler
    {
        return new ContactFormHandler();
    }
}