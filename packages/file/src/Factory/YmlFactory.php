<?php

namespace File\Factory;

use File\File;
use File\Filesystem\Type\YmlFilesystem;

class YmlFactory
{
    public static function getInstance(string $filename): File
    {
        $fileSystem = new YmlFilesystem();

        return new File($filename, $fileSystem);
    }
}