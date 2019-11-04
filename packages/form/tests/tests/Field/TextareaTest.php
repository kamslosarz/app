<?php


namespace tests\Field;


use Collection\Collection;
use Form\Field\Textarea;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class TextareaTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructTextarea()
    {
        $input = new Textarea([
            'name' => 'Textarea name',
            'value' => '123',
        ], [
            'option' => true
        ]);

        $reflection = new ReflectionClass($input);
        $attributes = $reflection->getProperty('attributes');
        $attributes->setAccessible(true);

        $options = $reflection->getProperty('options');
        $options->setAccessible(true);

        $this->assertEquals(new Collection([
            'name' => 'Textarea name',
            'value' => '123',
        ]), $attributes->getValue($input));

        $this->assertEquals(new Collection([
            'option' => true
        ]), $options->getValue($input));
    }
}
