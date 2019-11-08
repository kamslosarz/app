<?php


namespace tests\FileOperation;

use FileSystem\FileOperation\FileOperation;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class FileOperationTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldGetResults()
    {
        /** @var FileOperation $fileOperation */
        $fileOperation = Mockery::mock(FileOperation::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $expectedValue = 'test';

        $resultsProperty = new ReflectionProperty($fileOperation, 'results');
        $resultsProperty->setAccessible(true);
        $resultsProperty->setValue($fileOperation, $expectedValue);

        $this->assertEquals($expectedValue, $fileOperation->getResults());
    }
}