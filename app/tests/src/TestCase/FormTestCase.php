<?php

namespace Tests\TestCase;

use EventManager\Event\Context;
use Form\FormBuilder\FormBuilder;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Tests\FormExpectation;
use Validator\ConstraintBuilder\ConstraintBuilder;
use Validator\Validator;

abstract class FormTestCase extends TestCase
{
    public function getForm(string $classname, FormExpectation $formExpectation, MockInterface $contextMock = null)
    {
        if(!$contextMock)
        {
            $contextMock = Mockery::mock(Context::class);
        }
        $formBuilderMock = Mockery::mock(FormBuilder::class)
            ->shouldReceive('build')
            ->once()
            ->withNoArgs()
            ->getMock();
        foreach($formExpectation->getFieldsExpectations() as $expectation)
        {
            list($method, $args) = $expectation;
            $formBuilderMock->shouldReceive($method)
                ->once()
                ->with(...$args)
                ->andReturnSelf()
                ->getMock();
        }
        $constraintBuilderMock = Mockery::mock(ConstraintBuilder::class)
            ->shouldReceive('build')
            ->once()
            ->withNoArgs()
            ->getMock();
        foreach($formExpectation->getConstraintExpectations() as $args)
        {
            $constraintBuilderMock->shouldReceive('addConstraint')
                ->once()
                ->with(...$args)
                ->andReturnSelf()
                ->getMock();
        }

        $formValidatorMock = Mockery::mock(Validator::class)
            ->shouldReceive('getConstraintBuilder')
            ->once()
            ->andReturn($constraintBuilderMock)
            ->getMock()
            ->shouldReceive('setConstraintBuilder')
            ->once()
            ->andReturn($constraintBuilderMock)
            ->getMock();

        return new $classname($contextMock, $formBuilderMock, $formValidatorMock);
    }
}