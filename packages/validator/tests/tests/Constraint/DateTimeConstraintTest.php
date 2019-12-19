<?php

namespace tests\Constraint;

use PHPUnit\Framework\TestCase;
use Validator\Constraint\DateTimeConstraint;

class DateTimeConstraintTest extends TestCase
{

    public function testShouldValidateConstraintSuccess()
    {
        $datetimeConstraint = new DateTimeConstraint([
            'format' => 'Y-m-d H:i:s',
        ]);
        $date = date('Y-m-d H:i:s');
        $datetimeConstraint->setValue($date);

        $this->assertTrue($datetimeConstraint->isValid());
    }

    public function testShouldValidateConstraintFailed()
    {
        $datetimeConstraint = new DateTimeConstraint([
            'format' => 'Y-m-d H:i:s',
        ]);
        $date = date('Y-m-d H:i');
        $datetimeConstraint->setValue($date);

        $this->assertFalse($datetimeConstraint->isValid());
        $this->assertEquals('Invalid date '.$date.'. Correct format is Y-m-d H:i:s',$datetimeConstraint->getError());
    }
}