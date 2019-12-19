<?php

namespace tests\Constraint;

use PHPUnit\Framework\TestCase;
use Validator\Constraint\StringCompareConstraint;

class StringCompareConstraintTest extends TestCase
{
    public function testShouldValidateConstraintSuccessWithDefaultCompareStatement()
    {
        $stringCompareConstrain = new StringCompareConstraint([
            'expected' => 'expected string',
        ]);
        $stringCompareConstrain->setValue('expected string');
        $this->assertTrue($stringCompareConstrain->isValid());
    }

    public function testShouldValidateConstraintSuccessWithCustomCompareStatement()
    {
        $stringCompareConstrain = new StringCompareConstraint([
            'expected' => 'expected string',
            'compareStatement' => function ($expected, $actual) {
                return strcmp($actual, $expected) === 0;
            }
        ]);
        $stringCompareConstrain->setValue('expected string');
        $this->assertTrue($stringCompareConstrain->isValid());
    }

    public function testShouldValidateConstraintFailWithDefaultCompareStatement()
    {
        $stringCompareConstrain = new StringCompareConstraint([
            'expected' => 'expected string1',
        ]);
        $stringCompareConstrain->setValue('expected string');
        $this->assertFalse($stringCompareConstrain->isValid());
        $this->assertEquals('String \'expected string\' is not match \'expected string1\'', $stringCompareConstrain->getError());
    }

    public function testShouldValidateConstraintFailedWithCustomCompareStatement()
    {
        $stringCompareConstrain = new StringCompareConstraint([
            'expected' => 'expected string1',
            'compareStatement' => /**
             * @param $expected
             * @param $actual
             * @return bool
             */ function ($expected, $actual) {

                return false;
            }
        ]);
        $stringCompareConstrain->setValue('expected string');
        $this->assertFalse($stringCompareConstrain->isValid());
        $this->assertEquals('String \'expected string\' is not match \'expected string1\'', $stringCompareConstrain->getError());
    }
}