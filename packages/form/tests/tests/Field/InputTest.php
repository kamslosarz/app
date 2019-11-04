<?php


namespace tests\Field;


use Collection\Collection;
use Form\Field\Input;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class InputTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructInput()
    {
        $input = new Input([
            'name' => 'input name',
            'value' => '123',
            'type' => 'hidden'
        ], [
            'option' => false
        ]);

        $reflection = new ReflectionClass($input);
        $attributes = $reflection->getProperty('attributes');
        $attributes->setAccessible(true);

        $options = $reflection->getProperty('options');
        $options->setAccessible(true);

        $this->assertEquals(new Collection([
            'name' => 'input name',
            'value' => '123',
            'type' => 'hidden'
        ]), $attributes->getValue($input));

        $this->assertEquals(new Collection([
            'option' => false
        ]), $options->getValue($input));
    }
}
