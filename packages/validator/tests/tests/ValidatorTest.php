<?php

use PHPUnit\Framework\TestCase;
use Validator\Constraint\Constraint;
use Validator\ConstraintBuilder\ConstraintBuilder;
use Validator\Validator;

class ValidatorTest extends TestCase
{
    public function testShouldConstructValidator()
    {
        $validator = Mockery::mock(Validator::class)
            ->makePartial();

        $this->assertInstanceOf(Validator::class, $validator);
    }

    public function testShouldValidateConstraintsSuccessfully()
    {
        $value = 'test';
        $constraintBuilderMock = Mockery::mock(ConstraintBuilder::class)
            ->shouldReceive('build')
            ->andReturn([
                'name' => [
                    Mockery::mock(Constraint::class)
                        ->shouldReceive('isValid')
                        ->andReturnTrue()
                        ->getMock()
                        ->shouldReceive('setValue')
                        ->with($value)
                        ->once()
                        ->getMock()
                ]
            ])
            ->getMock();

        /** @var Validator $validator */
        $validator = Mockery::mock(Validator::class, [$constraintBuilderMock])
            ->makePartial();

        $this->assertTrue($validator->validate('name', $value));
    }

    public function testShouldValidateConstraintsFailed()
    {
        $value = 'test';
        $constraintBuilderMock = Mockery::mock(ConstraintBuilder::class)
            ->shouldReceive('build')
            ->andReturn([
                'name' => [
                    Mockery::mock(Constraint::class)
                        ->shouldReceive('isValid')
                        ->andReturnFalse()
                        ->getMock()
                        ->shouldReceive('setValue')
                        ->with($value)
                        ->once()
                        ->getMock()
                        ->shouldReceive('getError')
                        ->once()
                        ->andReturn('error message')
                        ->getMock()
                ]
            ])
            ->getMock();

        /** @var Validator $validator */
        $validator = Mockery::mock(Validator::class, [$constraintBuilderMock])
            ->makePartial();

        $this->assertFalse($validator->validate('name', $value));
        $this->assertEquals(['name' => ['error message']], $validator->getErrors());
    }

    public function testShouldValidateMultipleConstraintsFail()
    {
        $value = 'test';
        $constraintBuilderMock = Mockery::mock(ConstraintBuilder::class)
            ->shouldReceive('build')
            ->andReturn([
                'name' => [
                    Mockery::mock(Constraint::class)
                        ->shouldReceive('isValid')
                        ->andReturnFalse()
                        ->getMock()
                        ->shouldReceive('setValue')
                        ->with($value)
                        ->once()
                        ->getMock()
                        ->shouldReceive('getError')
                        ->once()
                        ->andReturn('error message')
                        ->getMock(),
                    Mockery::mock(Constraint::class)
                        ->shouldReceive('isValid')
                        ->andReturnFalse()
                        ->getMock()
                        ->shouldReceive('setValue')
                        ->with($value)
                        ->once()
                        ->getMock()
                        ->shouldReceive('getError')
                        ->once()
                        ->andReturn('error message2')
                        ->getMock()
                ],
                'name2' => [
                    Mockery::mock(Constraint::class)
                        ->shouldReceive('isValid')
                        ->andReturnFalse()
                        ->getMock()
                        ->shouldReceive('setValue')
                        ->with($value)
                        ->once()
                        ->getMock()
                        ->shouldReceive('getError')
                        ->once()
                        ->andReturn('error message')
                        ->getMock(),
                    Mockery::mock(Constraint::class)
                        ->shouldReceive('isValid')
                        ->andReturnFalse()
                        ->getMock()
                        ->shouldReceive('setValue')
                        ->with($value)
                        ->once()
                        ->getMock()
                        ->shouldReceive('getError')
                        ->once()
                        ->andReturn('error message2')
                        ->getMock()
                ]
            ])
            ->getMock();

        $validator = Mockery::mock(Validator::class, [$constraintBuilderMock])
            ->makePartial();

        /** @var Validator $validator */
        $this->assertFalse($validator->validate('name', $value));
        $this->assertFalse($validator->validate('name2', $value));
        $this->assertEquals([
            'name' => ['error message', 'error message2'], 'name2' => ['error message', 'error message2']
        ], $validator->getErrors());
    }
}