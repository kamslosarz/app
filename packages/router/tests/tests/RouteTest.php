<?php

use PHPUnit\Framework\TestCase;
use Router\Route;

class RouteTest extends TestCase
{
    public function testShouldConstructRoute()
    {
        $route = new Route('routeName');

        $this->assertInstanceOf(Route::class, $route);
    }

    public function testShouldGetRouteName()
    {
        $route = new Route('testRouteName');

        $this->assertEquals('testRouteName', $route->getName());
    }

    public function testShouldGetRouteParameters()
    {
        $routeParameters = [
            'parameter1' => 'value',
            'parameter2' => 'value',
        ];

        $route = new Route('testRouteName', $routeParameters);
        $this->assertEquals($routeParameters, $route->getParameters());
    }
}