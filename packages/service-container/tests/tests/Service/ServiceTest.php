<?php

namespace tests\Service;

use Mockery;
use PHPUnit\Framework\TestCase;
use ServiceContainer\Service\Service;

class ServiceTest extends TestCase
{
    public function testShouldConstructService()
    {
        $service = Mockery::mock(Service::class)->makePartial();
        $this->assertTrue(method_exists($service, '__invoke'));
    }
}