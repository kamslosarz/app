<?php

namespace Form;

use Collection\Collection;
use EventManager\Event\Context;
use Factory\FactoryException;
use Form\Field\Fields;
use Form\Field\FormField;
use Form\FormBuilder\FormBuilder;
use Form\Handler\FormHandler;
use Validator\ConstraintBuilder\ConstraintBuilder;
use Validator\Validator;

abstract class Form
{
    /** @var FormBuilder $formBuilder */
    private $formBuilder = [];
    /** @var Context */
    private $context;
    /** @var Validator $validator */
    private $validator;
    /** @var Collection $fields */
    private $fields;
    private $handler;
    /** @var Collection $attributes */
    protected $attributes = [];
    private $errors = [];

    /**
     * Form constructor.
     * @param Context $context
     * @param FormBuilder $formBuilder
     * @param Validator $validator
     * @throws FactoryException
     */
    public function __construct(Context $context, FormBuilder $formBuilder, Validator $validator)
    {
        $this->context = $context;
        $this->formBuilder = $formBuilder;
        $this->validator = $validator;
        $this->handler = $this->getHandler();
        $this->initialize();
    }

    /**
     * @throws FactoryException
     */
    private function initialize(): void
    {
        $this->buildFields($this->formBuilder, $this->context);
        $constraintBuilder = &$this->validator->getConstraintBuilder();
        $this->buildConstraints($constraintBuilder, $this->context);
        $this->fields = new Fields($this->formBuilder->build());
        $this->validator->setConstraintBuilder($constraintBuilder);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function handle(array $data): bool
    {
        /** @var FormField $field */
        foreach($data as $name => $value)
        {
            if($this->fields->has($name))
            {
                if($this->validator->validate($name, $value))
                {
                    $this->fields->set($name, $this->fields->get($name)->setValue($value));
                }
            }
            else
            {
                unset($data[$name]);
            }
        }

        if(empty($this->validator->getErrors()))
        {
            $this->handler->validate($data);
            if(!empty($this->handler->getErrors()))
            {
                $this->errors = $this->handler->getErrors();

                return false;
            }

            $this->handler->handle($data);

            return true;
        }

        $this->errors = $this->validator->getErrors();

        return false;
    }

    /**
     * @param FormBuilder $formBuilder
     * @param Context $context
     */
    abstract protected function buildFields(FormBuilder &$formBuilder, Context $context): void;

    /**
     * @param ConstraintBuilder $constraintBuilder
     * @param Context $context
     */
    abstract protected function buildConstraints(ConstraintBuilder &$constraintBuilder, Context $context): void;

    /**
     * @return FormHandler
     */
    abstract protected function getHandler(): FormHandler;

    /**
     * @return Collection
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    /**
     * @param array $attributes
     */
    protected function setAttributes(array $attributes): void
    {
        $this->attributes = new Collection($attributes);
    }

    /**
     * @return Collection
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->attributes['name'] ?? null;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}