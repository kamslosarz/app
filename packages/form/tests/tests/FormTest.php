<?php


namespace tests;


use EventManager\Event\Context;
use Form\Field\FormField;
use Form\Form;
use Form\FormBuilder\FormBuilder;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Validator\ConstraintBuilder\ConstraintBuilder;
use Validator\Validator;

class FormTest extends TestCase
{
    public function testShouldConstructForm()
    {
        $contextMock = Mockery::mock(Context::class);
        $constraintBuilder = Mockery::mock(ConstraintBuilder::class)
            ->shouldReceive('build')
            ->andReturn([])
            ->getMock();
        $formBuilderMock = Mockery::mock(FormBuilder::class)
            ->shouldReceive('build')
            ->andReturn([])
            ->getMock();
        $validatorMock = Mockery::mock(Validator::class)
            ->shouldReceive('getConstraintBuilder')
            ->andReturn($constraintBuilder)
            ->getMock()
            ->shouldReceive('setConstraintBuilder')
            ->with($constraintBuilder)
            ->getMock();
        $form = Mockery::mock(Form::class, [$contextMock, $formBuilderMock, $validatorMock])
            ->makePartial();

        $this->assertInstanceOf(Form::class, $form);
        $validatorMock->shouldHaveReceived('setConstraintBuilder')->once();
        $validatorMock->shouldHaveReceived('getConstraintBuilder')->once();
        $formBuilderMock->shouldHaveReceived('build')->once();
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldHandleFormSuccess()
    {
        $firstValue = 'test-value';
        $secondValue = 'other-value';

        $contextMock = Mockery::mock(Context::class);
        $firstFieldMock = Mockery::mock(FormField::class)
            ->shouldReceive('setValue')
            ->with($firstValue)
            ->getMock();
        $secondFieldMock = Mockery::mock(FormField::class)
            ->shouldReceive('setValue')
            ->with($secondValue)
            ->getMock();
        $formBuilderMock = $this->getFormBuilderMock([
            'name' => $firstFieldMock,
            'name2' => $secondFieldMock
        ]);
        $validatorMock = $this->getValidatorMock([true, true], []);
        $constraintBuilderMethod = (new ReflectionClass($validatorMock))->getMethod('getConstraintBuilder');
        $constraintBuilderMethod->setAccessible(true);
        $form = Mockery::mock(Form::class, [
            $contextMock, $formBuilderMock, $validatorMock
        ])->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('buildFields')
            ->with($formBuilderMock, $contextMock)
            ->andReturn([])
            ->getMock()
            ->shouldReceive('buildConstraints')
            ->with($constraintBuilderMethod->invoke($validatorMock), $contextMock)
            ->andReturn([])
            ->getMock();

        /** @var Form $form */
        $result = $form->handle([
            'name' => $firstValue,
            'name2' => $secondValue
        ]);
        $this->assertTrue($result);

        $form->shouldHaveReceived('buildFields')->once();
        $form->shouldHaveReceived('buildConstraints')->once();
        /** @var Mockery\MockInterface $validatorMock */
        $validatorMock->shouldHaveReceived('validate')->twice();
        $validatorMock->shouldHaveReceived('getErrors')->once();
        $validatorMock->shouldHaveReceived('getConstraintBuilder')->times(2);
        $validatorMock->shouldHaveReceived('setConstraintBuilder')->times(1);
        /** @var Mockery\MockInterface $formBuilderMock */
        $formBuilderMock->shouldHaveReceived('build')->once();
        $firstFieldMock->shouldHaveReceived('setValue')->once();
        $secondFieldMock->shouldHaveReceived('setValue')->once();
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldHandleFormFailed()
    {
        $contextMock = Mockery::mock(Context::class);
        $firstFieldMock = Mockery::mock(FormField::class)
            ->shouldNotReceive('setValue')
            ->getMock();
        $secondFieldMock = Mockery::mock(FormField::class)
            ->shouldNotReceive('setValue')
            ->getMock();
        $firstValue = 'test-value';
        $secondValue = 'other-value';

        $formBuilderMock = $this->getFormBuilderMock([
            'name' => $firstFieldMock,
            'name2' => $secondFieldMock
        ]);
        $validatorMock = $this->getValidatorMock([false, false], [
            'invalid field value',
            'invalid field value'
        ]);
        $constraintBuilderMethod = (new ReflectionClass($validatorMock))->getMethod('getConstraintBuilder');
        $constraintBuilderMethod->setAccessible(true);

        $form = Mockery::mock(Form::class, [
            $contextMock, $formBuilderMock, $validatorMock
        ])->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('buildFields')
            ->with($formBuilderMock, $contextMock)
            ->andReturn([])
            ->getMock()
            ->shouldReceive('buildConstraints')
            ->with($constraintBuilderMethod->invoke($validatorMock), $contextMock)
            ->andReturn([])
            ->getMock();

        /** @var Form $form */
        $result = $form->handle([
            'name' => $firstValue,
            'name2' => $secondValue
        ]);

        $this->assertFalse($result);

        /** @var Mockery\MockInterface $formBuilderMock */
        $formBuilderMock->shouldHaveReceived('build')->once();
        /** @var Mockery\MockInterface $validatorMock */
        $validatorMock->shouldHaveReceived('validate')->twice();
        $validatorMock->shouldHaveReceived('getConstraintBuilder')->times(2);
        $validatorMock->shouldHaveReceived('getErrors')->once();
        $form->shouldHaveReceived('buildFields')->once();
    }

    private function getFormBuilderMock(array $return): FormBuilder
    {
        return Mockery::mock(FormBuilder::class)
            ->shouldReceive('build')
            ->andReturn($return)
            ->getMock();
    }

    private function getValidatorMock(array $returnValues, array $errors): Validator
    {
        $constraintBuilderMock = Mockery::mock(ConstraintBuilder::class);
        $validatorMock = Mockery::mock(Validator::class)
            ->shouldReceive('getErrors')
            ->andReturn($errors)
            ->getMock()
            ->shouldReceive('validate')
            ->andReturnValues($returnValues)
            ->getMock()
            ->shouldReceive('getConstraintBuilder')
            ->twice()
            ->andReturn($constraintBuilderMock)
            ->getMock()
            ->shouldReceive('setConstraintBuilder')
            ->once()
            ->with($constraintBuilderMock)
            ->getMock();

        return $validatorMock;
    }
}