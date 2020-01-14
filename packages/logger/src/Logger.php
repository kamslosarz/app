<?php

namespace Logger;

class Logger
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function log($message): string
    {
        $file = $this->getFilename();
        file_put_contents($file,
            sprintf('[%s ssid: %s] %s ' . PHP_EOL, date('Y-m-d H:i:s'), session_id(), $message),
            FILE_APPEND
        );

        return $file;
    }

    protected function getFilename($part = 0): string
    {
        $filename = sprintf('%s/%s', $this->config['dir'], sprintf($this->config['filename'], date('Y-m-d'), $part));
        if(file_exists($filename) && strlen(file_get_contents($filename)) > $this->config['maxSize'])
        {
            return $this->getFilename($part + 1);
        }

        return $filename;
    }
}