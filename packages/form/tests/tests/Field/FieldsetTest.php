<?php


namespace tests\Field;


use Collection\Collection;
use Form\Field\Fieldset;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class FieldsetTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructFieldset()
    {
        $input = new Fieldset([
            'disabled' => 'disabled',
            'form' => 'form',
            'name' => 'name',
        ], [
            'legend' => 'some fieldset legend'
        ]);

        $reflection = new ReflectionClass($input);
        $attributes = $reflection->getProperty('attributes');
        $attributes->setAccessible(true);

        $options = $reflection->getProperty('options');
        $options->setAccessible(true);

        $this->assertEquals(new Collection([
            'disabled' => 'disabled',
            'form' => 'form',
            'name' => 'name'
        ]), $attributes->getValue($input));

        $this->assertEquals(new Collection([
            'legend' => 'some fieldset legend'
        ]), $options->getValue($input));
    }
}
