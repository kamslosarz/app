<?php


namespace tests\Field;


use Collection\Collection;
use Form\Field\Select;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class SelectTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructSelect()
    {
        $input = new Select([
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
        ]);

        $reflection = new ReflectionClass($input);
        $attributes = $reflection->getProperty('attributes');
        $attributes->setAccessible(true);

        $options = $reflection->getProperty('options');
        $options->setAccessible(true);

        $this->assertEquals(new Collection([
            'name' => 'Select name',
            'value' => 1,
        ]), $attributes->getValue($input));

        $this->assertEquals(new Collection([
            'option' => true,
            'multiple' => false,
            'options' => [
                1 => 'option1',
                2 => 'option1',
                3 => 'option1',
            ]
        ]), $options->getValue($input));
    }
}
