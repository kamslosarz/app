<?php

namespace tests\FileOperation;

use FileSystem\FileOperation\Remove;
use FileSystem\FileSystemException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class RemoveTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructRemoveFileOperation()
    {
        $filename = 'file.txt';
        $remove = new Remove($filename);

        $filenameProperty = new ReflectionProperty($remove, 'filename');
        $filenameProperty->setAccessible(true);

        $this->assertInstanceOf(Remove::class, $remove);
        $this->assertEquals($filename, $filenameProperty->getValue($remove));
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldExecuteRemoveFileOperationSuccess()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        touch($filename);
        $this->assertTrue(file_exists($filename));

        $remove = new Remove($filename);
        $remove->execute();
        $results = $remove->getResults();

        $this->assertTrue($results);
        $this->assertFalse(file_exists($filename));
    }


    public function testShouldExecuteRemoveFileOperationFailed()
    {
        $filename = 'file.txt';

        $this->expectExceptionMessage(sprintf('unlink(%s): No such file or directory', $filename));
        $this->expectException(FileSystemException::class);

        $remove = new Remove($filename);
        $remove->execute();
    }

    /**
     * @throws FileSystemException
     * @doesNotPerformAssertions
     */
    public function testShouldValidateRemoveFileOperationSuccess()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        touch($filename);

        $remove = new Remove($filename);

        try
        {
            $remove->validate();
            unlink($filename);
        }
        catch(FileSystemException $fileSystemException)
        {
            unlink($filename);

            throw $fileSystemException;
        }
    }

    public function testShouldRemoveFileOperationFailed()
    {
        $filename = 'file.txt';

        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage(sprintf('File \'%s\' is not deletable', $filename));

        $remove = new Remove($filename);
        $remove->validate();
    }
}