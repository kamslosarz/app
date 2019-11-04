<?php


namespace tests;

use Collection\ArrayAccessible;
use Mockery;
use PHPUnit\Framework\TestCase;

class ArrayAccessibleTest extends TestCase
{

    public function testShouldConvertToArray()
    {
        $parameters = [
            'p1', 'p2'
        ];
        /** @var ArrayAccessible $arrayAccessible */
        $arrayAccessible = Mockery::mock(ArrayAccessible::class, [$parameters])
            ->makePartial();

        $this->assertEquals($parameters, $arrayAccessible->__toArray());
    }
}