<?php


namespace tests\Constraint;


use PHPUnit\Framework\TestCase;
use Validator\Constraint\CharacterLengthConstraint;

class CharacterLengthConstraintTest extends TestCase
{
    public function testShouldValidateConstraintSuccess()
    {
        $characterLengthConstraint = new CharacterLengthConstraint([
            'min' => 1,
            'max' => 10
        ]);
        $characterLengthConstraint->setValue('123456789');
        $this->assertTrue($characterLengthConstraint->isValid());
    }

    public function testShouldValidateConstraintFailedToSmall()
    {
        $characterLengthConstraint = new CharacterLengthConstraint([
            'min' => 2,
            'max' => 10
        ]);
        $characterLengthConstraint->setValue('1');
        $this->assertFalse($characterLengthConstraint->isValid());
        $this->assertEquals('Value length is not in range. Got 1, expected 2..10', $characterLengthConstraint->getError());
    }

    public function testShouldValidateConstraintFailedToLarge()
    {
        $characterLengthConstraint = new CharacterLengthConstraint([
            'min' => 2,
            'max' => 3
        ]);
        $characterLengthConstraint->setValue('1234');
        $this->assertFalse($characterLengthConstraint->isValid());
        $this->assertEquals('Value length is not in range. Got 4, expected 2..3', $characterLengthConstraint->getError());
    }
}