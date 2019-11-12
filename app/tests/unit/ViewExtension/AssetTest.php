<?php

namespace unit\ViewExtension;

use App\ViewExtension\Asset;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

class AssetTest extends TestCase
{
    public function testShouldConstructViewExtension()
    {
        $asset = new Asset();

        $this->assertInstanceOf(Asset::class, $asset);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetExtensionFunctions()
    {
        $asset = new Asset();

        $getFunctionsMethod = new ReflectionMethod($asset, 'getFunctions');
        $getFunctionsMethod->setAccessible(true);
        $results = $getFunctionsMethod->invoke($asset);

        $this->assertEquals(['inlineCss'=>[$asset, 'inlineCss']], $results);
    }
}