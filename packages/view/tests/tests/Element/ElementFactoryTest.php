<?php


namespace tests\Element;

use Mockery;
use PHPUnit\Framework\TestCase;
use View\Element\Element;
use View\Element\ElementFactory;
use View\ViewExtension\ExtensionEventManager;

class ElementFactoryTest extends TestCase
{
    public function testShouldConstructElement()
    {
        $file = dirname(__DIR__) . '/fixture/resources/example-resource.phtml';
        $parameters = [];
        $paths = [dirname(__DIR__) . '/fixture/resources'];
        $extensionEventManager = Mockery::mock(ExtensionEventManager::class);
        $element = ElementFactory::getInstance($file, $paths, $parameters, $extensionEventManager);

        $this->assertInstanceOf(Element::class, $element);
    }
}