<?php


namespace unit\Context;

use App\Context\AppContext;
use Mockery;
use PHPUnit\Framework\TestCase;
use Router\Route;

class AppContextTest extends TestCase
{
    public function testShouldGetParameters()
    {
        $parameters = [
            'route' => Mockery::mock(Route::class)
                ->shouldReceive('getParameters')
                ->andReturn([
                    'p1', 'p2'
                ])
                ->getMock()
        ];
        $appContext = new AppContext($parameters);
        $result = $appContext->getParameters();

        $this->assertEquals(['p1', 'p2'], $result);
    }

    public function testShouldCheckIfHasParameters()
    {
        $parameters = ['route' => Mockery::mock(Route::class)];
        $appContext = new AppContext($parameters);
        $this->assertTrue($appContext->hasParameters());
    }
}