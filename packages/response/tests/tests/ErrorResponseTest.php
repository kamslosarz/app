<?php

use PHPUnit\Framework\TestCase;
use Response\ErrorResponse;

class ErrorResponseTest extends TestCase
{
    public function testShouldConstructErrorResponse()
    {
        $errorResponse = new ErrorResponse('error', ['error header'], 500);

        $this->assertEquals('error', $errorResponse->getContents());
        $this->assertEquals(['error header'], $errorResponse->getHeaders());
        $this->assertEquals(500, $errorResponse->getCode());
    }

    public function testShouldSetException()
    {
        $errorResponse = new ErrorResponse();
        $errorResponse->setException(new Exception('exception message'));

        ob_start();
        $errorResponse();

        $this->assertStringContainsString('exception message', $errorResponse->getContents());
        $this->assertStringContainsString('exception message', $this->getActualOutput());
        ob_end_clean();
    }
}