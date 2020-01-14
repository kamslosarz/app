<?php

namespace App\Logger;

use Logger\Logger;

abstract class LoggerFactory
{
    public static function getInstance(): Logger
    {
        return new Logger([
            'maxSize' => 1000000,
            'filename' => 'app-logs-%s.%s.log',
            'dir' => APP_DIR . '/data/logs'
        ]);
    }
}