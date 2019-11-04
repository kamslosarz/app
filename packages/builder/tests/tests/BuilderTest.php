<?php

use Builder\Builder;
use Factory\FactoryException;
use fixture\ExampleElementTuBuild;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public function testShouldCostructBuilder()
    {
        $builder = new Builder();

        $this->assertInstanceOf(Builder::class, $builder);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldAddElement()
    {
        $builder = new Builder();
        $parameters = [
            'p1' => 'v1'
        ];
        $parameters2 = [
            'p2' => 'v2'
        ];
        $reflection = new ReflectionClass($builder);
        $addMethod = $reflection->getMethod('add');
        $addMethod->setAccessible(true);
        $addMethod->invokeArgs($builder, ['name', $parameters]);
        $addMethod->invokeArgs($builder, ['name2', $parameters2]);

        $reflection = new ReflectionClass($builder);
        $elements = $reflection->getProperty('elements');
        $elements->setAccessible(true);

        $this->assertEquals([
            'name' => [$parameters],
            'name2' => [$parameters2]
        ], $elements->getValue($builder));
    }

    /**
     * @throws FactoryException
     * @throws ReflectionException
     */
    public function testShouldBuildElements()
    {
        $builder = new Builder();

        $reflection = new ReflectionClass($builder);
        $addMethod = $reflection->getMethod('add');
        $addMethod->setAccessible(true);
        $addMethod->invokeArgs($builder, [
            'test', [
                ExampleElementTuBuild::class,
                [
                    [
                        'param' => 'value'
                    ]
                ]
            ]
        ]);
        $items = $builder->build();
        $this->assertEquals(['test' => [new ExampleElementTuBuild(['param' => 'value'])]], $items);
    }

    /**
     * @throws FactoryException
     * @throws ReflectionException
     */
    public function testShouldThrowExceptionWhenElementClassNotExists()
    {
        $this->expectExceptionMessage('Class \'invalid class\' not exists');
        $this->expectException(FactoryException::class);
        $builder = new Builder();

        $reflection = new ReflectionClass($builder);
        $addMethod = $reflection->getMethod('add');
        $addMethod->setAccessible(true);
        $addMethod->invokeArgs($builder, [
            'test', [
                'invalid class'
            ]
        ]);
        $builder->build();
    }
}