<?php


namespace tests;


use Builder\ElementFactory\ElementFactory;
use Factory\FactoryException;
use fixture\ExampleElementTuBuild;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class ElementFactoryTest extends TestCase
{
    /**
     * @throws FactoryException
     * @throws ReflectionException
     */
    public function testShouldConstructElementSuccess()
    {
        $element = ElementFactory::getInstance([
            ExampleElementTuBuild::class, [
                ['p1', 'p2']
            ]
        ]);

        $reflection = new ReflectionClass($element);
        $parametersProperty = $reflection->getProperty('parameters');
        $parametersProperty->setAccessible(true);

        $this->assertInstanceOf(ExampleElementTuBuild::class, $element);
        $this->assertEquals(['p1', 'p2'], $parametersProperty->getValue($element));
    }
}