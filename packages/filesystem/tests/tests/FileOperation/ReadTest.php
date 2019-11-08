<?php


namespace tests\FileOperation;

use FileSystem\FileOperation\Read;
use FileSystem\FileSystemException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class ReadTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructReadFileOperation()
    {
        $filename = 'file.txt';
        $read = new Read($filename);

        $filenameProperty = new ReflectionProperty($read, 'filename');
        $filenameProperty->setAccessible(true);

        $this->assertEquals($filename, $filenameProperty->getValue($read));
        $this->assertInstanceOf(Read::class, $read);
    }

    /**
     * @throws FilesystemException
     * @doesNotPerformAssertions
     */
    public function testShouldValidateReadFileOperationSuccess()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        touch($filename);
        $read = new Read($filename);

        try
        {
            $read->validate();
            unlink($filename);
        }
        catch(FileSystemException $filesystemException)
        {
            unlink($filename);

            throw $filesystemException;
        }
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldValidateReadFileOperationFailed()
    {
        $filename = 'file.txt';
        $read = new Read($filename);

        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage(sprintf('File \'%s\' is not readable', $filename));

        $read->validate();
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldExecuteReadFileOperationSuccess()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        $contents = 'file contents';
        file_put_contents($filename, $contents);

        $read = new Read($filename);
        try
        {
            $read->execute();
            unlink($filename);
        }
        catch(FileSystemException $fileSystemException)
        {
            unlink($filename);

            throw $fileSystemException;
        }

        $this->assertEquals($contents, $read->getResults());
    }

    public function testShouldExecuteReadFileOperationFailed()
    {
        $filename = 'file.txt';
        $this->expectExceptionMessage(sprintf('File \'%s\' is not readable', $filename));
        $this->expectException(FileSystemException::class);

        $read = new Read($filename);
        $read->execute();
    }
}