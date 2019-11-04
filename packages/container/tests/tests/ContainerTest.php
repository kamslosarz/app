<?php


use App\ApplicationException;
use Collection\Collection;
use Container\Container;
use Container\Process\Process;
use Container\Process\ProcessContext;
use fixture\Process\ExampleProcess;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @throws ReflectionException
     * @throws ApplicationException
     */
    public function testShouldConstructContainer()
    {
        $dependencyTreeArray = [
            'appProcessContext' => new Collection(),
            'errorProcessContext' => new Collection()
        ];
        $container = new Container($dependencyTreeArray);

        $reflection = new ReflectionClass($container);
        $appProcessContext = $reflection->getProperty('appProcessContext');
        $appProcessContext->setAccessible(true);
        $errorProcessContext = $reflection->getProperty('errorProcessContext');
        $errorProcessContext->setAccessible(true);

        $this->assertEquals(new Collection(), $appProcessContext->getValue($container));
        $this->assertEquals(new Collection(), $errorProcessContext->getValue($container));
    }


    public function testShouldInvokeProcessTree()
    {
        $dependencyTree = [
            'appProcessContext' => new Collection([
                'exampleProcess' => [
                    ExampleProcess::class, ['parameters']
                ]
            ]),
            'errorProcessContext' => new Collection()
        ];
        $processMock = Mockery::mock(Process::class)
            ->shouldReceive('__invoke')
            ->andReturn('process invoked')
            ->getMock();
        /** @var Container $container */
        $container = Mockery::mock(Container::class, [$dependencyTree])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('factoryProcess')
            ->andReturn($processMock)
            ->getMock();

        /** @var ProcessContext $processContext */
        $processContext = $container->__invoke();
        $exampleProcessResults = $processContext->get('exampleProcess');

        $this->assertEquals('process invoked', $exampleProcessResults);
    }

    /**
     * @param $object
     * @param $name
     * @param $value
     * @throws ReflectionException
     */
    public function setProperty(&$object, $name, $value): void
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }
}