<?php

namespace unit\Controller\AbstractController;

use App\Controller\AbstractController\Controller;
use EventManager\Event\Context;
use fixture\ExampleForm;
use FlashMessenger\FlashMessenger;
use Form\Form;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;
use Request\Request;
use ServiceContainer\ServiceContainer;
use View\View;

class ControllerTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructController()
    {
        $contextMock = Mockery::mock(Context::class);
        $controller = Mockery::spy(Controller::class, [$contextMock])
            ->makePartial();

        $reflection = new ReflectionClass($controller);

        $contextProperty = $reflection->getProperty('context');
        $contextProperty->setAccessible(true);

        $this->assertEquals($contextProperty->getValue($controller), $contextMock);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetForm()
    {
        $contextMock = Mockery::mock(Context::class);
        $controller = Mockery::mock(Controller::class, [$contextMock])
            ->makePartial();

        $reflection = new ReflectionClass($controller);
        $getForm = $reflection->getMethod('getForm');
        $getForm->setAccessible(true);
        $form = $getForm->invokeArgs($controller, [
            ExampleForm::class
        ]);

        $this->assertInstanceOf(Form::class, $form);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetFormFailed()
    {
        $contextMock = Mockery::mock(Context::class);
        $controller = Mockery::mock(Controller::class, [$contextMock])
            ->makePartial();

        $reflection = new ReflectionClass($controller);
        $getForm = $reflection->getMethod('getForm');
        $getForm->setAccessible(true);
        $form = $getForm->invokeArgs($controller, [
            'invalid form class'
        ]);

        $this->assertNull($form);
    }

    public function testShouldGetView()
    {
        $viewMock = Mockery::mock(View::class);
        $this->assertContextItem($viewMock, 'view', 'getView');
    }

    public function testShouldGetServiceContainer()
    {
        $serviceContainerMock = Mockery::mock(ServiceContainer::class);
        $this->assertContextItem($serviceContainerMock, 'serviceContainer', 'getServiceContainer');
    }

    public function testShouldGetRequest()
    {
        $requestMock = Mockery::mock(Request::class);
        $this->assertContextItem($requestMock, 'request', 'getRequest');
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGeFlashMessenger()
    {
        $requestMock = Mockery::mock(Request::class);
        $contextMock = Mockery::mock(Context::class)
            ->shouldReceive('get')
            ->once()
            ->with('request')
            ->andReturn($requestMock)
            ->getMock();

        $controller = Mockery::mock(Controller::class, [$contextMock])
            ->makePartial();

        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getFlashMessenger');
        $method->setAccessible(true);
        $result = $method->invoke($controller);

        $contextMock->shouldHaveReceived('get')->with('request')->once();
        $this->assertInstanceOf(FlashMessenger::class, $result);
    }

    /**
     * @param $itemMock
     * @param $contextPropertyName
     * @param $controllerMethod
     * @throws ReflectionException
     */
    private function assertContextItem($itemMock, $contextPropertyName, $controllerMethod)
    {
        $contextMock = Mockery::mock(Context::class)
            ->shouldReceive('get')
            ->once()
            ->with($contextPropertyName)
            ->andReturn($itemMock)
            ->getMock();

        $controller = Mockery::mock(Controller::class, [$contextMock])
            ->makePartial();

        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod($controllerMethod);
        $method->setAccessible(true);
        $result = $method->invoke($controller);

        $contextMock->shouldHaveReceived('get')->with($contextPropertyName)->once();
        $this->assertEquals($itemMock, $result);
    }
}