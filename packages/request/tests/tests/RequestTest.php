<?php

use PHPUnit\Framework\TestCase;
use Request\Request;

class RequestTest extends TestCase
{
    public function testShouldConstructRequest()
    {
        $request = new Request();

        $this->assertInstanceOf(Request::class, $request);
    }

    public function testShouldInitializeParameters()
    {
        $_GET = ['test' => 'value'];
        $_POST = ['test' => 'value2'];
        $_SERVER = ['test' => 'value3'];
        $_COOKIE = ['test' => 'value4'];
        $_SESSION = ['test' => 'value5'];

        $request = new Request();

        $this->assertEquals($request->getQuery()->__toArray(), $_GET);
        $this->assertEquals($request->getPost()->__toArray(), $_POST);
        $this->assertEquals($request->getServer()->__toArray(), $_SERVER);
        $this->assertEquals($request->getCookie()->__toArray(), $_COOKIE);
        $this->assertEquals($request->getSession()->__toArray(), $_SESSION);
    }
}