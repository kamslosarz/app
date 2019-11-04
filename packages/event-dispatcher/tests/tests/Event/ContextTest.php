<?php


namespace tests\Event;

use EventManager\Event\Context;
use Mockery;
use PHPUnit\Framework\TestCase;

class ContextTest extends TestCase
{
    public function testShouldConstructContext()
    {
        $context = Mockery::mock(Context::class);

        $this->assertInstanceOf(Context::class, $context);
    }
}