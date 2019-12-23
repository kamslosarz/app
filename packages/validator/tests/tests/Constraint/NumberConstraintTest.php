<?php

namespace tests\Constraint;

use PHPUnit\Framework\TestCase;
use Validator\Constraint\NumberConstraint;

class NumberConstraintTest extends TestCase
{
    public function testShouldValidateConstraintSuccess()
    {
        $numberConstraint = new NumberConstraint([
            'min' => 1,
            'max' => 10
        ]);
        $numberConstraint->setValue(9);
        $this->assertTrue($numberConstraint->isValid());
    }

    public function testShouldValidateConstraintFailedToSmall()
    {
        $numberConstraint = new NumberConstraint([
            'min' => 2,
            'max' => 10
        ]);
        $numberConstraint->setValue(1);
        $this->assertFalse($numberConstraint->isValid());
        $this->assertEquals('Value is not in range. Got 1, expected 2..10', $numberConstraint->getError());
    }

    public function testShouldValidateConstraintFailedToHighNumber()
    {
        $numberConstraint = new NumberConstraint([
            'min' => 2,
            'max' => 3
        ]);
        $numberConstraint->setValue(4);
        $this->assertFalse($numberConstraint->isValid());
        $this->assertEquals('Value is not in range. Got 4, expected 2..3', $numberConstraint->getError());
    }
}