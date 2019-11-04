<?php


namespace tests\Field;


use Collection\Collection;
use Form\Field\Button;
use Form\Field\FormField;
use Form\Field\Input;
use Form\Field\Select;
use Form\Field\Textarea;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class FieldTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructFormField()
    {
        $attributesArray = [
            'name' => 'exampleField'
        ];
        $optionsArray = [
            'option' => 'value'
        ];

        /** @var FormField $formField */
        $formField = Mockery::mock(FormField::class, [$attributesArray, $optionsArray])->makePartial();

        $reflection = new ReflectionClass($formField);
        $attributes = $reflection->getProperty('attributes');
        $attributes->setAccessible(true);
        $options = $reflection->getProperty('options');
        $options->setAccessible(true);

        $this->assertEquals(new Collection($attributesArray), $attributes->getValue($formField));
        $this->assertEquals(new Collection($optionsArray), $options->getValue($formField));
        $this->assertEquals('exampleField', $formField->getName());
    }


    /**
     * @param string $className
     * @param array $attributes
     * @param array $options
     * @throws ReflectionException
     * @dataProvider fieldsSuccess
     */
    public function testShouldCheckIfFieldsConstructs(string $className, array $attributes, array $options)
    {
        $formField = new $className($attributes, $options);
        $reflection = new ReflectionClass($formField);
        $attributesProperty = $reflection->getProperty('attributes');
        $attributesProperty->setAccessible(true);
        $optionsProperty = $reflection->getProperty('options');
        $optionsProperty->setAccessible(true);

        $this->assertEquals(new Collection($attributes), $attributesProperty->getValue($formField));
        $this->assertEquals(new Collection($options), $optionsProperty->getValue($formField));
    }

    public function fieldsSuccess()
    {
        return [
            'input test  case ' => [
                Input::class,
                [
                    'name' => 'input name',
                    'value' => '123',
                    'type' => 'hidden'
                ], [
                    'option' => false
                ]
            ],
            'textarea test  case ' => [
                Textarea::class,
                [
                    'name' => 'Textarea name',
                    'value' => '123',
                ], [
                    'option' => true
                ]
            ],
            'select test  case ' => [
                Select::class,
                [
                    'name' => 'Select name',
                    'value' => 1,
                ], [
                    'option' => true,
                    'multiple' => false,
                    'options' => [
                        1 => 'option1',
                        2 => 'option1',
                        3 => 'option1',
                    ]
                ]
            ],
            'button test  case ' => [
                Button::class,
                [
                    'name' => 'Button name',
                    'value' => 1,
                ], [
                    'option' => true,
                    'multiple' => false,
                ]
            ]
        ];
    }
}
