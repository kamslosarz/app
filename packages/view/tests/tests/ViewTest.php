<?php

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use View\Element\Element;
use View\View;
use View\ViewException;
use View\ViewExtension\ExtensionEventManager;
use View\ViewExtension\ViewExtension;

class ViewTest extends TestCase
{
    public function testShouldConstructView()
    {
        $resourcesPath = [dirname(__DIR__).'/fixture/resources/'];
        $extensionEventManagerMock = Mockery::mock(ExtensionEventManager::class);
        $view = new View($resourcesPath, $extensionEventManagerMock);

        $this->assertInstanceOf(View::class, $view);
    }

    /**
     * @throws ViewException
     */
    public function testShouldRenderView()
    {
        $resourcesPath = [dirname(__DIR__).'/fixture/resources/'];
        $viewElementMock = Mockery::mock(Element::class)
            ->shouldReceive('__invoke')
            ->andReturn('invoked')
            ->getMock();

        /** @var View $view */
        $view = Mockery::mock(View::class, [$resourcesPath, Mockery::mock(ExtensionEventManager::class)])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('factoryElement')
            ->with('example-resource.phtml', [])
            ->andReturn($viewElementMock)
            ->getMock();

        $output = $view->render('example-resource.phtml', []);

        /**@var MockInterface $view */
        $view->shouldHaveReceived('factoryElement')->once();
        $this->assertEquals('invoked', $output);
    }

    /**
     * @throws ViewException
     */
    public function testShouldRenderViewWithNoSpaces()
    {
        ob_start();
        include dirname(__DIR__).'/fixture/resources/example-resource-with-spaces.phtml';
        $content = ob_get_clean();

        $resourcesPath = [dirname(__DIR__).'/fixture/resources/'];
        $viewElementMock = Mockery::mock(Element::class)
            ->shouldReceive('__invoke')
            ->andReturn($content)
            ->getMock();

        /** @var View $view */
        $view = Mockery::mock(View::class, [$resourcesPath, Mockery::mock(ExtensionEventManager::class)])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('factoryElement')
            ->with('example-resource.phtml', [])
            ->andReturn($viewElementMock)
            ->getMock();

        $output = $view->render('example-resource.phtml', [], true);

        /**@var MockInterface $view */
        $view->shouldHaveReceived('factoryElement')->once();
        $this->assertEquals('<html><form><input/><button>save</button></form></html>', $output);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldFactoryElement()
    {
        $resourcesPath = [dirname(__DIR__).'/fixture/resources/'];
        $extensionEventManagerMock = Mockery::mock(ExtensionEventManager::class);
        $view = new View($resourcesPath, $extensionEventManagerMock);

        $reflection = new ReflectionClass($view);
        $factoryElement = $reflection->getMethod('factoryElement');
        $factoryElement->setAccessible(true);
        $viewElement = $factoryElement->invokeArgs($view, [
            'example-resource.phtml', ['parameters']
        ]);

        $this->assertInstanceOf(Element::class, $viewElement);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldAddExtensionEventManagerListeners()
    {
        $extensionMock = Mockery::mock(ViewExtension::class);
        $extensionEventManagerMock = Mockery::mock(ExtensionEventManager::class)
            ->shouldReceive('addSubscriber')
            ->with($extensionMock)
            ->getMock()
            ->shouldReceive('getListeners')
            ->andReturn([
                'listener'
            ])
            ->getMock();

        $view = new View(['path/to/resources'], $extensionEventManagerMock);

        $view->addExtension($extensionMock);

        $reflection = new ReflectionClass($view);
        $extensionEventManagerProperty = $reflection->getProperty('extensionManager');
        $extensionEventManagerProperty->setAccessible(true);

        /** @var ExtensionEventManager $extensionEventManager */
        $extensionEventManager = $extensionEventManagerProperty->getValue($view);

        $this->assertInstanceOf(ExtensionEventManager::class, $extensionEventManager);
        $this->assertEquals(['listener'], $extensionEventManager->getListeners());
    }
}