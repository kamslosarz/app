<?php


namespace fixture;

use fixture\Constraint\EmailConstraint;
use Validator\ConstraintBuilder\ConstraintBuilder;
use Validator\Validator;

class EmailValidator extends Validator
{
    protected function build(ConstraintBuilder $constraintBuilder): void
    {
        $constraintBuilder->addConstraint(EmailConstraint::class, ['message' => 'error!']);
    }
}