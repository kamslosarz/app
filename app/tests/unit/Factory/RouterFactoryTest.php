<?php


namespace unit\Factory;

use App\Factory\RouterFactory;
use Container\Process\ProcessContext;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;
use Router\Router;

class RouterFactoryTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldInvokeRouterFactory()
    {
        $parameters = [
            'route1', 'route2'
        ];
        $routerFactory = new RouterFactory($parameters);

        $processContextMock = Mockery::mock(ProcessContext::class);
        $results = $routerFactory->__invoke($processContextMock);

        $routesProperty = new ReflectionProperty($results, 'routes');
        $routesProperty->setAccessible(true);
        $routes = $routesProperty->getValue($results);

        $this->assertInstanceOf(Router::class, $results);
        $this->assertEquals($parameters, $routes);
    }
}