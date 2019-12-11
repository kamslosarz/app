<?php

namespace unit\Factory;

use App\ApplicationException;
use App\Factory\ViewFactory;
use Container\Process\ProcessContext;
use Factory\FactoryException;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;
use Request\Request;
use View\View;
use View\ViewExtension\ExtensionEventManager;

class ViewFactoryTest extends TestCase
{
    /**
     * @throws ApplicationException
     * @throws FactoryException
     * @throws ReflectionException
     */
    public function testShouldInvokeViewFactory()
    {
        $parameters = [
            'resources' => [
                'path/to/resources',
                'path/to/other/resources'
            ],
            'viewExtensions' => include FIXTURE_DIR . '/config/view-extensions.php'
        ];
        $requestMock = Mockery::mock(Request::class);
        $processContextMock = Mockery::mock(ProcessContext::class)
            ->shouldReceive('get')
            ->with('request')
            ->andReturn($requestMock)
            ->getMock();

        $viewFactory = new ViewFactory($parameters);
        $results = $viewFactory->__invoke($processContextMock);

        $extensionManagerProperty = new ReflectionProperty($results, 'extensionManager');
        $extensionManagerProperty->setAccessible(true);
        $extensionManager = $extensionManagerProperty->getValue($results);

        $resourcesPathsProperty = new ReflectionProperty($results, 'resourcesPaths');
        $resourcesPathsProperty->setAccessible(true);
        $resourcesPaths = $resourcesPathsProperty->getValue($results);

        $listenersProperty = new ReflectionProperty($extensionManager, 'listeners');
        $listenersProperty->setAccessible(true);
        $listeners = $listenersProperty->getValue($extensionManager);

        $this->assertInstanceOf(ExtensionEventManager::class, $extensionManager);
        $this->assertEquals([
            'path/to/resources',
            'path/to/other/resources'
        ], $resourcesPaths);
        $this->assertInstanceOf(View::class, $results);
        $this->assertEquals([
            'inlineCss',
            'include',
            'flashMessages',
            'hasMessagesToFlash',
        ], array_keys($listeners));
    }
}