<?php


namespace Constraint;

use Factory\FactoryException;
use fixture\Constraint\EmailConstraint;
use PHPUnit\Framework\TestCase;
use Validator\ConstraintBuilder\ConstraintBuilder;

class ConstraintBuilderTest extends TestCase
{
    public function testShouldConstructConstraintBuilder()
    {
        $constraintBuilder = new ConstraintBuilder();

        $this->assertInstanceOf(ConstraintBuilder::class, $constraintBuilder);
    }

    /**
     * @throws FactoryException
     */
    public function testShouldBuildConstraints()
    {
        $constraintBuilder = new ConstraintBuilder();
        $constraintBuilder->addConstraint('name', EmailConstraint::class, ['message' => 'Email \'%s\' is not valid']);
        $constraints = $constraintBuilder->build();

        $this->assertEquals([
            'name' => [
                new EmailConstraint(['message' => 'Email \'%s\' is not valid'])
            ]
        ], $constraints);
    }
}