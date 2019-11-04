<?php


namespace tests\FormViewTest;

use ArrayIterator;
use Collection\Collection;
use Form\Field\FormField;
use Form\Form;
use Form\FormView\FormView;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class FormViewTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructFormView()
    {
        $fieldMock = Mockery::mock(FormField::class)
            ->shouldReceive([
                'getAttributes' => new Collection([]),
                'getOptions' => new Collection([])
            ])->getMock();

        $formFieldCollectionMock = Mockery::mock(Collection::class)
            ->shouldReceive('getIterator')
            ->andReturn(
                Mockery::mock(ArrayIterator::class)
                    ->shouldReceive([
                        'rewind' => null,
                        'current' => $fieldMock,
                        'next' => null,
                    ])
                    ->getMock()
                    ->shouldReceive('valid')
                    ->once()
                    ->andReturnTrue()
                    ->getMock()
                    ->shouldReceive('valid')
                    ->once()
                    ->andReturnFalse()
                    ->getMock()

            )
            ->getMock()
            ->shouldReceive('serialize')
            ->getMock();

        $formAttributes = new Collection([
            'method' => 'post',
            'name' => 'form-name'
        ]);
        $formMock = Mockery::mock(Form::class)
            ->shouldReceive('getFields')
            ->andReturn($formFieldCollectionMock)
            ->getMock()
            ->shouldReceive('getAttributes')
            ->andReturn($formAttributes)
            ->getMock();

        $formView = new FormView($formMock);

        $formMock->shouldHaveReceived('getFields')->once();
        $elementsProperty = (new ReflectionClass($formView))->getProperty('htmlElements');
        $elementsProperty->setAccessible(true);

        $attributesProperty = (new ReflectionClass($formView))->getProperty('attributes');
        $attributesProperty->setAccessible(true);

        $htmlElements = $elementsProperty->getValue($formView);

        $this->assertInstanceOf(Collection::class, $htmlElements);
        $this->assertCount(1, $htmlElements);
        $this->assertEquals($formAttributes, $attributesProperty->getValue($formView));
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetResourcePath()
    {
        $formFieldMock = Mockery::mock(FormField::class)
            ->makePartial();
        $formView = Mockery::mock(FormView::class)
            ->makePartial();

        $reflection = new ReflectionClass($formView);
        $getResourceMethod = $reflection->getMethod('getResource');
        $getResourceMethod->setAccessible(true);
        $path = $getResourceMethod->invokeArgs($formView, [$formFieldMock]);

        $this->assertEquals('mockery_0_form_field_formfield.phtml', $path);
    }
}