<?php

namespace tests\Constraint;

use Mockery;
use PHPUnit\Framework\TestCase;
use Validator\Constraint\Constraint;

class ConstraintTest extends TestCase
{
    public function testShouldConstructConstraint()
    {
        $constraint = Mockery::mock(Constraint::class);
        $this->assertInstanceOf(Constraint::class, $constraint);
    }

    public function testShouldValidateConstraintSuccess()
    {
        $email = 'test@test.pl';

        /** @var Constraint $constraint */
        $constraint = Mockery::mock(Constraint::class, [
            'test' => 'test'
        ])
            ->makePartial()
            ->shouldReceive('isValid')
            ->andReturnTrue()
            ->getMock()
            ->shouldNotReceive('getError')
            ->getMock();

        $constraint->setValue($email);
        $this->assertTrue($constraint->isValid());
    }

    public function testShouldValidateConstraintFail()
    {
        $email = 'invalid email address @ ada.[pl';
        /** @var Constraint $constraint */
        $constraint = Mockery::mock(Constraint::class, [['test' => 123]])
            ->makePartial()
            ->shouldReceive('isValid')
            ->andReturnFalse()
            ->getMock()
            ->shouldReceive('getError')
            ->andReturn('error')
            ->getMock();

        $constraint->setValue($email);

        $this->assertFalse($constraint->isValid());
        $this->assertEquals('error', $constraint->getError());
    }
}