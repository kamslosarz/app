<?php

namespace File\Factory;

use File\File;
use File\Filesystem\Type\FlatFilesystem;

class FlatFactory
{
    public static function getInstance(string $filename): File
    {
        $fileSystem = new FlatFilesystem();

        return new File($filename, $fileSystem);
    }
}