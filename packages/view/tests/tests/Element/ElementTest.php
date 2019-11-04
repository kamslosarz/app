<?php


namespace tests\Element;

use EventManager\Event\ContextException;
use EventManager\EventManagerException;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;
use View\Element\Element;
use View\ViewException;
use View\ViewExtension\ExtensionEventManager;

class ElementTest extends TestCase
{
    public function testShouldConstructElement()
    {
        $resourceFile = 'tmp_file.phtml';
        $parameters = [];
        $paths = [dirname(dirname(__DIR__)).'/fixture/resources'];
        $extensionEventManagerMock = Mockery::mock(ExtensionEventManager::class);
        $element = new Element($resourceFile, $paths, $parameters, $extensionEventManagerMock);

        $this->assertInstanceOf(Element::class, $element);
    }

    /**
     * @throws ContextException
     * @throws EventManagerException
     * @throws ViewException
     */
    public function testShouldGetPresetVariable()
    {
        $resourceFile = 'tmp_file.phtml';
        $paths = [dirname(dirname(__DIR__)).'/fixture/resources'];
        $parameters = [
            'preset' => true
        ];
        $extensionEventManagerMock = Mockery::mock(ExtensionEventManager::class);
        $element = new Element($resourceFile, $paths, $parameters, $extensionEventManagerMock);

        $this->assertTrue($element->__get('preset'));
    }

    /**
     * @throws ContextException
     * @throws EventManagerException
     * @throws ViewException
     */
    public function testShouldInvokeExtensionMethod()
    {
        $resourceFile = 'tmp_file.phtml';
        $paths = [dirname(dirname(__DIR__)).'/fixture/resources'];
        $parameters = [
            'preset' => true
        ];
        $extensionEventManagerMock = Mockery::mock(ExtensionEventManager::class)
            ->shouldReceive('hasListener')
            ->once()
            ->andReturnTrue()
            ->getMock()
            ->shouldReceive('getListeners')
            ->once()
            ->andReturn([
                'someMethod' => [
                    function () {
                        return 'invoked';
                    }
                ]
            ])
            ->getMock();

        $element = new Element($resourceFile, $paths, $parameters, $extensionEventManagerMock);

        $this->assertEquals('invoked', $element->__call('someMethod', ['param1', 'param2']));
    }

    /**
     * @throws ViewException
     * @throws ContextException
     * @throws EventManagerException
     */
    public function testShouldAccessExtensionVariable()
    {
        $resourceFile = 'tmp_file.phtml';
        $paths = [dirname(dirname(__DIR__)).'/fixture/resources'];
        $parameters = [
            'preset' => true
        ];
        $extensionEventManagerMock = Mockery::mock(ExtensionEventManager::class)
            ->shouldReceive('hasListener')
            ->with('extensionVariable')
            ->andReturnTrue()
            ->getMock()
            ->shouldReceive('getListeners')
            ->once()
            ->andReturn([
                'extensionVariable' => [
                    function (): string {
                        return 'extensionVariableValue';
                    }
                ]
            ])
            ->getMock();

        $element = new Element($resourceFile, $paths, $parameters, $extensionEventManagerMock);
        $extensionVariable = $element->__get('extensionVariable');
        $this->assertEquals('extensionVariableValue', $extensionVariable);
    }

    /**
     * @throws ViewException
     */
    public function testShouldHandleElementThatThrowException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('View exception occurred');

        $file = 'some-broken-file.phtml';
        $paths = [dirname(dirname(__DIR__)).'/fixture/resources'];
        $element = new Element($file, $paths, [], Mockery::mock(ExtensionEventManager::class));
        $element->__invoke();
    }

    /**
     * @throws ViewException
     */
    public function testShouldInvokeElement()
    {
        $element = new Element(
            'example-resource.phtml',
            [dirname(dirname(__DIR__)).'/fixture/resources'],
            ['variable' => 'variable value'],
            Mockery::mock(ExtensionEventManager::class)
        );
        $results = $element->__invoke();
        $expected = <<<EOD
<html>
variable value</html>
EOD;
        $this->assertEquals($expected, $results);
    }

    /**
     * @throws ViewException
     */
    public function testShouldThrowExceptionWhenInvalidResourcePath()
    {
        $path = get_include_path();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid resource path \'\'');

        $file = 'resources/some-file.phtml';
        $paths = [null];
        $element = new Element($file, $paths, [], Mockery::mock(ExtensionEventManager::class));
        $element->__invoke();

        $this->assertEquals($path, get_include_path());
    }

    /**
     * @throws ViewException
     */
    public function testShouldThrowExceptionWhenFileNotExistsInResourcePath()
    {
        $file = 'resources/some-file.phtml';
        $paths = [dirname(dirname(__DIR__)).'/fixture/resources'];

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(sprintf('File \'%s\' not exists in \'%s\'', $file, dirname(dirname(__DIR__)).'/fixture/resources'));

        $element = new Element($file, $paths, [], Mockery::mock(ExtensionEventManager::class));
        $element->__invoke();
    }
}