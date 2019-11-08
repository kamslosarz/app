<?php

namespace tests\File;

use FileSystem\File\File;
use FileSystem\FileSystem;
use FileSystem\FileSystemException;
use Mockery;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class FileTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructFile()
    {
        $filename = 'file.txt';
        $fileSystemMock = Mockery::mock(FileSystem::class);

        $file = new File($filename, $fileSystemMock);
        $fileNameProperty = new ReflectionProperty($file, 'filename');
        $fileNameProperty->setAccessible(true);
        $fileSystemProperty = new ReflectionProperty($file, 'fileSystem');
        $fileSystemProperty->setAccessible(true);

        $this->assertEquals($filename, $fileNameProperty->getValue($file));
        $this->assertEquals($fileSystemMock, $fileSystemProperty->getValue($file));

        $this->assertInstanceOf(File::class, $file);
    }

    public function testShouldInvokeFilesystemSaveFile()
    {
        $filename = 'file.txt';
        $contents = 'file contents';

        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('save')
            ->once()
            ->with($filename, $contents)
            ->andReturnTrue()
            ->getMock();

        $file = new File($filename, $fileSystemMock);

        $result = $file->save($contents);
        $fileSystemMock->shouldHaveReceived('save')->once();

        $this->assertTrue($result);
    }

    public function testShouldInvokeFilesystemSaveFileAndReturnFalse()
    {
        $filename = 'file.txt';
        $contents = 'file contents';

        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('save')
            ->once()
            ->with($filename, $contents)
            ->andReturnFalse()
            ->getMock();

        $file = new File($filename, $fileSystemMock);
        $result = $file->save($contents);

        $fileSystemMock->shouldHaveReceived('save')->once();
        $this->assertFalse($result);
    }

    public function testShouldInvokeFilesystemSaveFileAndReturnFalseOnException()
    {
        $filename = 'file.txt';
        $contents = 'file contents';
        $exceptionMessage = 'error';
        $exceptionMock = Mockery::mock(FileSystemException::class, [
            $exceptionMessage
        ]);

        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('save')
            ->once()
            ->with($filename, $contents)
            ->andThrows($exceptionMock)
            ->getMock();

        $file = new File($filename, $fileSystemMock);
        $result = $file->save($contents);

        $fileSystemMock->shouldHaveReceived('save')->once();
        $this->assertEquals($exceptionMessage, $file->getError());
        $this->assertFalse($result);
    }

    public function testShouldInvokeFilesystemReadFile()
    {
        $filename = 'file.txt';
        $contents = 'file contents';

        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('read')
            ->once()
            ->with($filename)
            ->andReturn($contents)
            ->getMock();

        $file = new File($filename, $fileSystemMock);

        $result = $file->read();
        $fileSystemMock->shouldHaveReceived('read')->once();

        $this->assertEquals($contents, $result);
    }

    public function testShouldInvokeFilesystemReadFileAndReturnFalse()
    {
        $filename = 'file.txt';
        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('read')
            ->once()
            ->with($filename)
            ->andReturnFalse()
            ->getMock();

        $file = new File($filename, $fileSystemMock);
        $result = $file->read();

        $fileSystemMock->shouldHaveReceived('read')->once();
        $this->assertEquals('', $result);
    }

    public function testShouldInvokeFilesystemReadFileAndReturnFalseOnException()
    {
        $filename = 'file.txt';
        $exceptionMessage = 'error';
        $exceptionMock = Mockery::mock(FileSystemException::class, [
            $exceptionMessage
        ]);

        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('read')
            ->once()
            ->with($filename)
            ->andThrows($exceptionMock)
            ->getMock();

        $file = new File($filename, $fileSystemMock);
        $result = $file->read();

        $fileSystemMock->shouldHaveReceived('read')->once();
        $this->assertEquals($exceptionMessage, $file->getError());
        $this->assertEquals('', $result);
    }

    public function testShouldInvokeFilesystemRemoveFile()
    {
        $filename = 'file.txt';
        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('remove')
            ->once()
            ->with($filename)
            ->andReturnTrue()
            ->getMock();

        $file = new File($filename, $fileSystemMock);

        $result = $file->remove();
        $fileSystemMock->shouldHaveReceived('remove')->once();
        $this->assertTrue($result);
    }

    public function testShouldInvokeFilesystemRemoveFileAndReturnFalse()
    {
        $filename = 'file.txt';
        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('remove')
            ->once()
            ->with($filename)
            ->andReturnFalse()
            ->getMock();

        $file = new File($filename, $fileSystemMock);
        $result = $file->remove();

        $fileSystemMock->shouldHaveReceived('remove')->once();
        $this->assertFalse($result);
    }

    public function testShouldInvokeFilesystemRemoveFileAndReturnFalseOnException()
    {
        $filename = 'file.txt';
        $exceptionMessage = 'error';
        $exceptionMock = Mockery::mock(FileSystemException::class, [
            $exceptionMessage
        ]);

        $fileSystemMock = Mockery::mock(FileSystem::class)
            ->shouldReceive('remove')
            ->once()
            ->with($filename)
            ->andThrows($exceptionMock)
            ->getMock();

        $file = new File($filename, $fileSystemMock);
        $result = $file->remove();

        $fileSystemMock->shouldHaveReceived('remove')->once();
        $this->assertEquals($exceptionMessage, $file->getError());
        $this->assertFalse($result);
    }
}