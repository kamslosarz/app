<?php


use Factory\Factory;
use Factory\FactoryException;
use fixture\ExampleClassToFactory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @throws FactoryException
     */
    public function testShouldConstructElementSuccess()
    {
        $item = Factory::getInstance([
            ExampleClassToFactory::class,
            [['p1', 'p2']]
        ]);

        $this->assertInstanceOf(ExampleClassToFactory::class, $item);
    }

    /**
     * @param string $exceptionMessage
     * @param array $parameters
     * @throws FactoryException
     * @dataProvider ConstructElementFailed
     */
    public function testShouldConstructElementFailed(string $exceptionMessage, array $parameters)
    {
        $this->expectException(FactoryException::class);
        $this->expectExceptionMessage($exceptionMessage);

        Factory::getInstance($parameters);
    }

    public function ConstructElementFailed(): array
    {
        return [
            'Undefined class' => [
                'Undefined element class',
                []
            ],
            'Classname must be string' => [
                'Classname must be a string. Given: \'array\'', [
                    []
                ]
            ],
            'Invalid class' => [
                'Class \'InvalidClass\' not exists', [
                    'InvalidClass'
                ]
            ]
        ];
    }
}