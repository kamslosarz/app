<?php

namespace tests\Factory;

use File\Factory\YmlFactory;
use File\Filesystem\Type\YmlFilesystem;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class YmlFactoryTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructFileWithYmlFilesystem()
    {
        $file = YmlFactory::getInstance('file.yml');

        $reflection = new ReflectionClass($file);

        $fileSystemProperty = $reflection->getProperty('filesystem');
        $fileSystemProperty->setAccessible(true);
        $fileSystem = $fileSystemProperty->getValue($file);

        $this->assertInstanceOf(YmlFilesystem::class, $fileSystem);
    }
}