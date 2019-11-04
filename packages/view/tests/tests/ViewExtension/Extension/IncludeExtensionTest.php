<?php


namespace tests\ViewExtension\Extension;


use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use View\View;
use View\ViewException;
use View\ViewExtension\Extension\IncludeExtension;

class IncludeExtensionTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldGetFunctions()
    {
        $viewMock = Mockery::mock(View::class);
        $includeExtension = new IncludeExtension($viewMock);

        $reflection = new ReflectionClass($includeExtension);
        $getFunctionsMethod = $reflection->getMethod('getFunctions');
        $getFunctionsMethod->setAccessible(true);

        $this->assertEquals([
            'include' => [
                [$includeExtension, 'include']
            ]
        ], $getFunctionsMethod->invoke($includeExtension));
    }

    /**
     * @throws ViewException
     * @doesNotPerformAssertions
     */
    public function testShouldIncludeFile()
    {
        $viewMock = Mockery::mock(View::class)
            ->shouldReceive('render')
            ->with('file', ['parameters'], false)
            ->getMock();
        $includeExtension = new IncludeExtension($viewMock);
        $includeExtension->include('file', ['parameters'], false);

        $viewMock->shouldHaveReceived('render')->once();
    }
    /**
     * @throws ViewException
     * @doesNotPerformAssertions
     */
    public function testShouldIncludeFileWithNoSpaces()
    {
        $viewMock = Mockery::mock(View::class)
            ->shouldReceive('render')
            ->with('file', ['parameters'], true)
            ->getMock();
        $includeExtension = new IncludeExtension($viewMock);
        $includeExtension->include('file', ['parameters'], true);

        $viewMock->shouldHaveReceived('render')->once();
    }
}