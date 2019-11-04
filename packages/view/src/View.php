<?php

namespace View;

use View\Element\Element;
use View\Element\ElementFactory;
use View\ViewExtension\ExtensionEventManager;
use View\ViewExtension\ViewExtension;

class View
{
    protected $resourcesPaths;
    private $extensionManager;

    public function __construct(array $resourcesPaths, ExtensionEventManager $extensionEventManager)
    {
        $this->resourcesPaths = $resourcesPaths;
        $this->extensionManager = $extensionEventManager;
    }

    /**
     * @param $file
     * @param array $parameters
     * @param bool $noSpaces
     * @return string
     * @throws ViewException
     */
    public function render($file, array $parameters = [], bool $noSpaces = false): string
    {
        $content = $this->factoryElement($file, $parameters)();
        if($noSpaces)
        {
            return $this->stripSpaces($content);
        }
        return $content;
    }

    /**
     * @param $file
     * @param array $parameters
     * @return Element
     */
    protected function factoryElement($file, array $parameters = []): Element
    {
        return ElementFactory::getInstance($file, $this->resourcesPaths, $parameters, $this->extensionManager);
    }

    /**
     * @param ViewExtension $viewExtension
     */
    public function addExtension(ViewExtension $viewExtension): void
    {
        $this->extensionManager->addSubscriber($viewExtension);
    }

    public function addResourcePath(string $resourcesPath): void
    {
        $this->resourcesPaths[] = $resourcesPath;
    }

    private function stripSpaces(string $content)
    {
        return trim(preg_replace('/>\s+</', '><', $content));
    }
}