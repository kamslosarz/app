<?php

namespace App\Controller\Api\Backups;

use App\Controller\AbstractController\AppController;
use Validator\Constraint\CharacterLengthConstraint;
use Validator\Constraint\DateTimeConstraint;
use Validator\ConstraintBuilder\ConstraintBuilder;

abstract class BackupController extends AppController
{
    protected function getConstraintBuilder(): ConstraintBuilder
    {
        $constraintBuilder = new ConstraintBuilder();
        $constraintBuilder->addConstraint('name', CharacterLengthConstraint::class, [
            'max' => 255,
            'min' => 1,
        ])->addConstraint('description', CharacterLengthConstraint::class, [
            'max' => 255,
            'min' => 1,
        ])->addConstraint('date', DateTimeConstraint::class, [
            'format' => 'Y-m-d',
        ]);

        return $constraintBuilder;
    }
}