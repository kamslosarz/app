<?php


namespace tests\ViewExtension;


use Mockery;
use PHPUnit\Framework\TestCase;
use View\ViewExtension\ViewExtension;

class ViewExtensionTest extends TestCase
{
    public function testShouldGetSubscribedEvents()
    {
        $viewExtension = Mockery::mock(ViewExtension::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('getFunctions')
            ->andReturn(['some functions'])
            ->getMock();

        /** @var ViewExtension $viewExtension */
        $results = $viewExtension->getSubscribedEvents();

        $this->assertEquals(['some functions'], $results);
        $viewExtension->shouldHaveReceived('getFunctions')->once();
    }
}