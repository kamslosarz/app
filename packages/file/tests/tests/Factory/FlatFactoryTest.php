<?php

namespace tests\Factory;

use File\Factory\FlatFactory;
use File\Filesystem\Type\FlatFilesystem;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class FlatFactoryTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructFileWithYmlFilesystem()
    {
        $file = FlatFactory::getInstance('file.yml');

        $reflection = new ReflectionClass($file);

        $fileSystemProperty = $reflection->getProperty('filesystem');
        $fileSystemProperty->setAccessible(true);
        $fileSystem = $fileSystemProperty->getValue($file);

        $this->assertInstanceOf(FlatFilesystem::class, $fileSystem);
    }
}