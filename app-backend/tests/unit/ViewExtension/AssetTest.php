<?php

namespace unit\ViewExtension;

use App\ViewExtension\AssetExtension;
use Container\Process\ProcessContext;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use View\View;

class AssetTest extends TestCase
{
    public function testShouldConstructViewExtension()
    {
        $viewMock = Mockery::mock(View::class);
        $processContextMock = Mockery::mock(ProcessContext::class);
        $asset = new AssetExtension($viewMock, $processContextMock);

        $this->assertInstanceOf(AssetExtension::class, $asset);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetExtensionFunctions()
    {
        $viewMock = Mockery::mock(View::class);
        $processContextMock = Mockery::mock(ProcessContext::class);
        $asset = new AssetExtension($viewMock, $processContextMock);

        $getFunctionsMethod = new ReflectionMethod($asset, 'getFunctions');
        $getFunctionsMethod->setAccessible(true);
        $results = $getFunctionsMethod->invoke($asset);

        $this->assertEquals(['inlineCss'=>[$asset, 'inlineCss']], $results);
    }
}