<?php

use PHPUnit\Framework\TestCase;
use Response\Response;

class ResponseTest extends TestCase
{
    public function testShouldConstructResponse()
    {
        $response = new Response();

        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @runInSeparateProcess
     */
    public function testShouldInvokeResponse()
    {
        $response = new Response('<html></html>', ['Content-type: text/plain;charset=UTF-16'], 200);
        ob_start();
        $response();
        $this->assertEquals(['Content-type: text/plain;charset=UTF-16'], xdebug_get_headers());
        $this->assertEquals(200, http_response_code());
        $this->assertStringContainsString('<html></html>', $this->getActualOutput());
        ob_end_clean();
    }
}