<?php

use PHPUnit\Framework\TestCase;
use Request\Request;
use Router\Exception\RouterException;
use Router\Router;

class RouterTest extends TestCase
{
    public function testshouldConstructRouter()
    {
        $router = new Router([]);

        $this->assertInstanceOf(Router::class, $router);
    }

    /**
     * @throws RouterException
     */
    public function testShouldMatchSimpleRouteWithParameters()
    {
        $routes = include_once dirname(__DIR__).'/fixture/routes.php';
        $router = new Router($routes);
        $route = $router->getRoute($this->getRequestMock('GET', '/request'));

        $this->assertEquals('/{parameter}', $route->getName());
    }

    /**
     * @throws RouterException
     */
    public function testShouldMatchSimpleRouteWithNoParameters()
    {
        $routes = include_once dirname(__DIR__).'/fixture/routes.php';
        $router = new Router($routes);
        $route = $router->getRoute($this->getRequestMock('GET', '/test/simple/route/'));

        $this->assertEquals('/test/simple/route/', $route->getName());
    }

    /**
     * @throws RouterException
     */
    public function testShouldMatchRouteWithParameters()
    {
        $routes = include_once dirname(__DIR__).'/fixture/routes.php';
        $router = new Router($routes);

        $route = $router->getRoute($this->getRequestMock('get', '/route/123/321/test'));
        $this->assertEquals('/route/{parameter}/{secondParameter}/{p}', $route->getName());
        $this->assertEquals(['123', '321', 'test'], $route->getParameters());
    }

    /**
     * @throws RouterException
     */
    public function testShouldThrowRouteNotFoundException()
    {
        $this->expectException(RouterException::class);
        $routes = include_once dirname(__DIR__).'/fixture/routes.php';

        $router = new Router($routes);
        $router->getRoute($this->getRequestMock('get', 'route-that-not-exists'));
    }

    /**
     * @throws RouterException
     */
    public function testShouldGetDefaultRoute()
    {
        $routes = [
            '*' => []
        ];
        $router = new Router($routes);
        $route = $router->getRoute($this->getRequestMock('get', '/route/123/321/test'));

        $this->assertEquals('*', $route->getName());
        $this->assertEquals([], $route->getParameters());
    }

    /**
     * @throws RouterException
     */
    public function testShouldGetRouteAdequateToRequestMethod()
    {
        $routes = [
            'get:/' => 1,
            'post:/' => 2,
            'get,post:/example' => 3
        ];

        $router = new Router($routes);

        $route = $router->getRoute($this->getRequestMock('get', '/'));
        $this->assertEquals(['get:/', []], [$route->getName(), $route->getParameters()]);

        $route = $router->getRoute($this->getRequestMock('post', '/'));
        $this->assertEquals(['post:/', []], [$route->getName(), $route->getParameters()]);

        $route = $router->getRoute($this->getRequestMock('post', '/example'));
        $this->assertEquals(['get,post:/example', []], [$route->getName(), $route->getParameters()]);

        $route = $router->getRoute($this->getRequestMock('get', '/example'));
        $this->assertEquals(['get,post:/example', []], [$route->getName(), $route->getParameters()]);
    }

    /**
     * @param $method
     * @param $uri
     * @return Request
     */
    private function getRequestMock(string $method, string $uri): Request
    {
        return Mockery::mock(Request::class)
            ->shouldReceive('getRequestMethod')
            ->andReturn($method)
            ->getMock()
            ->shouldReceive('getRequestUri')
            ->andReturn($uri)
            ->getMock();
    }
}