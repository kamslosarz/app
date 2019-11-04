<?php


namespace tests\Element;


use PHPUnit\Framework\TestCase;
use View\Element\ViewContext;

class ViewContextTest extends TestCase
{

    public function testShouldGetParameters()
    {
        $parameters = ['p1' => 'v1'];
        $context = new ViewContext($parameters);

        $this->assertEquals($parameters, $context->getParameters());
    }
}