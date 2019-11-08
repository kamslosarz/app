<?php

use App\Controller\Controller;
use EventManager\Event\Context;
use fixture\ExampleForm;
use FlashMessenger\FlashMessenger;
use Form\Form;
use PHPUnit\Framework\TestCase;
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
    public function testShouldGetView()
    {
        $viewMock = Mockery::mock(View::class);
        $contextMock = Mockery::mock(Context::class)
            ->shouldReceive('get')
            ->once()
            ->with('view')
            ->andReturn($viewMock)
            ->getMock();

        $controller = Mockery::mock(Controller::class, [$contextMock])
            ->makePartial();

        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getView');
        $method->setAccessible(true);
        $view = $method->invoke($controller);

        $contextMock->shouldHaveReceived('get')->with('view')->once();
        $this->assertEquals($viewMock, $view);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetServiceContainer()
    {
        $serviceContainerMock = Mockery::mock(ServiceContainer::class);
        $contextMock = Mockery::mock(Context::class)
            ->shouldReceive('get')
            ->once()
            ->with('serviceContainer')
            ->andReturn($serviceContainerMock)
            ->getMock();

        $controller = Mockery::mock(Controller::class, [$contextMock])
            ->makePartial();

        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getServiceContainer');
        $method->setAccessible(true);
        $serviceContainer = $method->invoke($controller);

        $contextMock->shouldHaveReceived('get')->with('serviceContainer')->once();
        $this->assertEquals($serviceContainerMock, $serviceContainer);
    }
    /**
     * @throws ReflectionException
     */
    public function testShouldGetRequest()
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
        $method = $reflection->getMethod('getRequest');
        $method->setAccessible(true);
        $result = $method->invoke($controller);

        $contextMock->shouldHaveReceived('get')->with('request')->once();
        $this->assertEquals($requestMock, $result);
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

        $flashMessengerMock = Mockery::mock(FlashMessenger::class);
        $controller = Mockery::mock(Controller::class, [$contextMock])
            ->makePartial();

        $reflection = new ReflectionClass($controller);
        $method = $reflection->getMethod('getFlashMessenger');
        $method->setAccessible(true);
        $result = $method->invoke($controller);

        $contextMock->shouldHaveReceived('get')->with('request')->once();
        $this->assertInstanceOf(FlashMessenger::class, $result);
    }
}