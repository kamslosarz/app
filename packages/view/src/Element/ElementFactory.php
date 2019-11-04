<?php

namespace View\Element;

use View\ViewExtension\ExtensionEventManager;

abstract class ElementFactory
{
    /**
     * @var array
     */

    /**
     * @param string $resourceFile
     * @param array $resourcesPaths
     * @param array $parameters
     * @param ExtensionEventManager $extensionEventManager
     * @return Element
     */
    public static function getInstance(string $resourceFile,
        array $resourcesPaths,
        array $parameters,
        ExtensionEventManager $extensionEventManager)
    {
        return new Element($resourceFile, $resourcesPaths, $parameters, $extensionEventManager);
    }
}