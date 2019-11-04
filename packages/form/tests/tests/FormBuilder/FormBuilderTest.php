<?php

namespace tests;

use fixture\FormField\ExampleFormField;
use Form\Field\Button;
use Form\Field\Fieldset;
use Form\Field\Input;
use Form\Field\Select;
use Form\Field\Textarea;
use Form\FormBuilder\FormBuilder;
use Form\FormBuilder\FormBuilderException;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class FormBuilderTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldAddField()
    {
        /** @var FormBuilder $formBuilder */
        $formBuilder = Mockery::mock(FormBuilder::class)
            ->makePartial();

        $field = [
            ExampleFormField::class,
            [
                'name' => 'example field',
            ],
            [
                'option' => true
            ]
        ];
        $reflection = new ReflectionClass($formBuilder);
        $addField = $reflection->getMethod('addField');
        $addField->setAccessible(true);
        $addField->invokeArgs($formBuilder, $field);

        $elements = $reflection->getProperty('elements');
        $elements->setAccessible(true);

        $this->assertEquals([
            [
                ExampleFormField::class,
                [
                    [
                        'name' => 'example field',
                    ],
                    [
                        'option' => true
                    ]
                ]
            ]
        ], $elements->getValue($formBuilder)['fields']);
    }

    /**
     * @param FormBuilder $formBuilder
     * @param array $assertion
     * @throws ReflectionException
     * @dataProvider buildFormData
     */
    public function testShouldBuildForm(FormBuilder $formBuilder, array $assertion)
    {
        $reflection = new ReflectionClass($formBuilder);
        $elements = $reflection->getProperty('elements');
        $elements->setAccessible(true);
        $fields = $elements->getValue($formBuilder);

        $this->assertEquals($assertion, $fields['fields']);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldThrowExceptionWhenFieldAttributeNameIsEmpty()
    {
        $this->expectException(FormBuilderException::class);
        $this->expectExceptionMessage(sprintf('Field attribute \'name\' cannot be empty. Given: \'%s\'', print_r([], true)));

        $formBuilder = new FormBuilder();
        $reflection = new ReflectionClass($formBuilder);
        $addField = $reflection->getMethod('addField');
        $addField->setAccessible(true);
        $addField->invokeArgs($formBuilder, [ExampleFormField::class, [], []]);
    }

    public function buildFormData()
    {
        return [
            'input' => [
                (function (): FormBuilder {
                    $formBuilder = new FormBuilder();
                    $formBuilder->addInput([
                        'a1' => 'v1',
                        'name' => 'name'
                    ], [
                        'validate' => 'true'
                    ]);
                    return $formBuilder;
                })(),
                [
                    [
                        Input::class,
                        [
                            [
                                'a1' => 'v1',
                                'name' => 'name'
                            ],
                            [
                                'validate' => 'true'
                            ]
                        ]
                    ]
                ]
            ],
            'select' => [
                (function (): FormBuilder {
                    $formBuilder = new FormBuilder();
                    $formBuilder->addSelect([
                        'a1' => 'v1',
                        'name' => 'name'
                    ], [
                        'validate' => 'true',
                        'multiple' => true,
                        'options' =>
                            [
                                1 => 'label',
                                2 => 'label 2'
                            ]
                    ]);

                    return $formBuilder;
                })(),
                [
                    [
                        Select::class,
                        [
                            [
                                'a1' => 'v1',
                                'name' => 'name'
                            ],
                            [
                                'validate' => 'true',
                                'multiple' => true,
                                'options' => [
                                    1 => 'label',
                                    2 => 'label 2'
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'textarea' => [
                (function (): FormBuilder {
                    $formBuilder = new FormBuilder();
                    $formBuilder->addTextarea([
                        'a1' => 'v1',
                        'name' => 'name'
                    ], [
                        'validate' => 'true',
                    ]);

                    return $formBuilder;
                })(),
                [
                    [
                        Textarea::class,
                        [
                            [
                                'a1' => 'v1',
                                'name' => 'name'
                            ],
                            [
                                'validate' => 'true',
                            ]
                        ]
                    ]
                ]
            ],
            'button' => [
                (function (): FormBuilder {
                    $formBuilder = new FormBuilder();
                    $formBuilder->addButton([
                        'a1' => 'v1',
                        'name' => 'name'
                    ], [
                        'validate' => 'true',
                    ]);

                    return $formBuilder;
                })(),
                [
                    [
                        Button::class,
                        [
                            [
                                'a1' => 'v1',
                                'name' => 'name'
                            ],
                            [
                                'validate' => 'true',
                            ]
                        ]
                    ]
                ]
            ],
            'fieldset' => [
                (function (): FormBuilder {
                    $formBuilder = new FormBuilder();
                    $formBuilder->addFieldset([
                        'a1' => 'v1',
                        'name' => 'name'
                    ], 'fieldset legend');

                    return $formBuilder;
                })(),
                [
                    [
                        Fieldset::class,
                        [
                            [
                                'a1' => 'v1',
                                'name' => 'name'
                            ],
                            ['legend' => 'fieldset legend']
                        ]
                    ]
                ]
            ]
        ];
    }
}