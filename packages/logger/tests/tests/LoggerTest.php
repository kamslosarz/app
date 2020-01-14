<?php

namespace tests\Field;

use Exception;
use Logger\Logger;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    public function testShouldConstructLogger()
    {
        $logger = new Logger([]);

        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function testShouldLogToFile()
    {
        $logsDir = dirname(__DIR__) . '/fixture/logs';
        $filename = 'test.log';
        session_start();

        if(file_exists($logsDir . $filename))
        {
            unlink($logsDir . $filename);
        }

        $logger = new Logger([
            'maxSize' => 1000000,
            'filename' => $filename,
            'dir' => $logsDir
        ]);

        $file = $logger->log('test');
        $this->assertNotEmpty($file);
        $this->assertRegExp('/\[[0-9\-\: ]+ssid: [a-z0-9]+\] test/', file_get_contents($file));

        unlink($file);
    }

    public function testShouldAddDateAndPartToFile()
    {
        $logsDir = dirname(__DIR__) . '/fixture/logs';
        $filename = '%s-%s-test.log';

        $logger = new Logger([
            'maxSize' => 1000000,
            'filename' => $filename,
            'dir' => $logsDir
        ]);

        $file = $logger->log('test');
        $this->assertRegExp('/[0-9]{4}-[0-9]{2}-[0-9]{2}-[0-9]+-test\.log/', $file);
        unlink($file);
    }

    public function testShouldAddSecondPartToFile()
    {
        $logsDir = dirname(__DIR__) . '/fixture/logs';
        $filename = 'test-%s-%s.log';
        session_start();

        $logger = new Logger([
            'maxSize' => 1,
            'filename' => $filename,
            'dir' => $logsDir
        ]);

        $realFilename = sprintf('%s/%s', $logsDir, sprintf($filename, date('Y-m-d'), '0'));
        file_put_contents($realFilename, 'some contents');

        try
        {
            $file = $logger->log('test');
            $this->assertEquals('some contents', file_get_contents($realFilename));
            $this->assertRegExp('/\[[0-9\-\: ]+ssid: [a-z0-9]+\] test/', file_get_contents($file));
        }
        catch(Exception $e)
        {
            unlink($realFilename);
            unlink($file);

            throw $e;
        }
        unlink($realFilename);
        unlink($file);

    }
}