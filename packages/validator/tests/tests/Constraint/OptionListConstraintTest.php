<?php

namespace tests\Constraint;

use PHPUnit\Framework\TestCase;
use Validator\Constraint\OptionListConstraint;

class OptionListConstraintTest extends TestCase
{
    public function testShouldValidateConstraintSuccess()
    {
        $optionListConstraint = new OptionListConstraint([
            'options' => [
                'option' => 'label',
                'option2' => 'label2',
                'option3' => 'label3',
            ]
        ]);
        $optionListConstraint->setValue('option');
        $this->assertTrue($optionListConstraint->isValid());
    }

    public function testShouldValidateConstraintFailed()
    {
        $optionListConstraint = new OptionListConstraint([
            'options' => [
                'option' => 'label',
                'option2' => 'label2',
                'option3' => 'label3',
            ]
        ]);
        $optionListConstraint->setValue('1');

        $this->assertFalse($optionListConstraint->isValid());
        $this->assertEquals('Option \'1\' not in range \option, option2, option3\'', $optionListConstraint->getError());
    }
}