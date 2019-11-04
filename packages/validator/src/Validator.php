<?php

namespace Validator;

use Factory\FactoryException;
use Validator\Constraint\Constraint;
use Validator\ConstraintBuilder\ConstraintBuilder;

abstract class Validator
{
    protected array $constraints;
    protected array $errors = [];
    /**
     * @var ConstraintBuilder
     */
    protected ConstraintBuilder $constraintBuilder;

    /**
     * Validator constructor.
     * @param ConstraintBuilder $constraintBuilder
     * @throws FactoryException
     */
    public function __construct(ConstraintBuilder $constraintBuilder)
    {
        $this->constraints = $constraintBuilder->build();
        $this->constraintBuilder = $constraintBuilder;
    }

    /**
     * @param string $name
     * @param $value
     * @return bool
     */
    public function validate(string $name, $value): bool
    {
        /** @var Constraint $constraint */
        if(isset($this->constraints[$name]))
        {
            foreach($this->constraints[$name] as $constraint)
            {
                $constraint->setValue($value);
                if(!$constraint->isValid())
                {
                    $this->errors[$name][] = $constraint->getError();
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function &getConstraintBuilder(): ConstraintBuilder
    {
        return $this->constraintBuilder;
    }

    /**
     * @param ConstraintBuilder $constraintBuilder
     * @throws FactoryException
     */
    public function setConstraintBuilder(ConstraintBuilder $constraintBuilder): void
    {
        $this->constraints = $constraintBuilder->build();
        $this->constraintBuilder = $constraintBuilder;
    }

    /**
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }
}