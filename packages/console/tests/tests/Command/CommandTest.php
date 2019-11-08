<?php

namespace tests\Command;

use Collection\Collection;
use Console\Command\Command;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class CommandTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructCommand()
    {
        $parameters = Mockery::mock(Collection::class);
        $command = Mockery::mock(Command::class, [
            $parameters
        ])
            ->makePartial();

        $parametersProperty = new ReflectionProperty($command, 'parameters');
        $parametersProperty->setAccessible(true);
        $parametersPropertyValue = $parametersProperty->getValue($command);

        $this->assertInstanceOf(Collection::class, $parametersPropertyValue);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldGetCommandResultsSuccess()
    {
        $expectedResults = 'expected results';
        $parameter = Mockery::mock(Collection::class);
        $command = Mockery::mock(Command::class, [
            $parameter
        ])
            ->makePartial();

        $resultsProperty = new ReflectionProperty($command, 'results');
        $resultsProperty->setAccessible(true);
        $resultsProperty->setValue($command, $expectedResults);

        $results = $command->getResults();

        $this->assertEquals($expectedResults, $results);
    }
}