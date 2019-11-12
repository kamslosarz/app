<?php


namespace tests\FormViewTest;


use Collection\Collection;
use Form\FormView\AttributesAsString;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class AttributesAsStringTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldGetAttributesAsString()
    {
        /** @var AttributesAsString $attributesAsString */
        $attributesAsString = Mockery::mock(AttributesAsString::class)
            ->makePartial();

        $attributes = [
            'class' => 'element-class',
            'id' => 'SOME_ID'
        ];
        $reflection = new ReflectionClass($attributesAsString);
        $attributesProperty = $reflection->getProperty('attributes');
        $attributesProperty->setAccessible(true);
        $attributesProperty->setValue($attributesAsString, new Collection($attributes));

        $this->assertEquals(' class="element-class" id="SOME_ID"', $attributesAsString->getAttributesAsString());
    }
}