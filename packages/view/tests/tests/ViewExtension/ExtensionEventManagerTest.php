<?php


namespace tests\ViewExtension;

use PHPUnit\Framework\TestCase;
use View\ViewExtension\ExtensionEventManager;

class ExtensionEventManagerTest extends TestCase
{
    public function testShouldCheckIfHasListenerSuccess()
    {
        $extensionEventManager = new ExtensionEventManager();
        $extensionEventManager->addListener('test', []);

        $this->assertTrue($extensionEventManager->hasListener('test'));;
    }

    public function testShouldCheckIfHasListenerFailed()
    {
        $extensionEventManager = new ExtensionEventManager();
        $this->assertFalse($extensionEventManager->hasListener('test'));;
    }
}