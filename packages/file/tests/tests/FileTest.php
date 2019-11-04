<?php

use File\File;
use File\Filesystem\Filesystem;
use File\Filesystem\FilesystemException;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testShouldConstructFile()
    {
        $file = Mockery::mock(File::class);

        $this->assertInstanceOf(File::class, $file);
    }

    public function testShouldSaveFileSuccess()
    {
        $filename = 'filename.txt';
        $fileSystem = Mockery::mock(Filesystem::class)
            ->shouldReceive('save')
            ->once()
            ->getMock();

        $file = new File($filename, $fileSystem);
        $result = $file->save();

        $fileSystem->shouldHaveReceived('save')->with([$filename]);
        $this->assertTrue($result);
    }

    public function testShouldSaveFileFailed()
    {
        $filename = 'filename.txt';
        $exceptionMock = Mockery::mock(FilesystemException::class, [
            'file is broken'
        ]);
        $fileSystem = Mockery::mock(Filesystem::class)
            ->shouldReceive('save')
            ->once()
            ->andThrows($exceptionMock)
            ->getMock();

        $file = new File($filename, $fileSystem);
        $result = $file->save();

        $fileSystem->shouldHaveReceived('save')->with([$filename]);
        $this->assertFalse($result);
        $this->assertEquals('file is broken', $file->getError());
    }

    public function testShouldReadFileSuccess()
    {
        $filename = 'some-file.txt';
        $fileSystemMock = Mockery::mock(Filesystem::class)
            ->shouldReceive('read')
            ->once()
            ->andReturn('file-content')
            ->getMock();

        $file = new File($filename, $fileSystemMock);

        $result = $file->read();

        $this->assertEquals('file-content', $result);
        $fileSystemMock->shouldHaveReceived('read')->with([$filename]);
    }

    public function testShouldReadFileFailed()
    {
        $filename = 'some-file.txt';
        $exceptionMock = Mockery::mock(FilesystemException::class, [
            'file is broken'
        ]);
        $fileSystemMock = Mockery::mock(Filesystem::class)
            ->shouldReceive('read')
            ->once()
            ->andThrows($exceptionMock)
            ->getMock();

        $file = new File($filename, $fileSystemMock);

        $result = $file->read();

        $this->assertEquals('file is broken', $file->getError());
        $this->assertFalse($result);
        $fileSystemMock->shouldHaveReceived('read')->with([$filename]);
    }

    public function testShouldDeleteFileSuccess()
    {
        $filename = 'some-file.txt';
        $fileSystemMock = Mockery::mock(Filesystem::class)
            ->shouldReceive('delete')
            ->once()
            ->getMock();

        $file = new File($filename, $fileSystemMock);

        $result = $file->delete();

        $this->assertTrue($result);
        $fileSystemMock->shouldHaveReceived('delete')->with([$filename]);

    }

    public function testShouldDeleteFileFailed()
    {
        $filename = 'some-file.txt';
        $exceptionMock = Mockery::mock(FilesystemException::class, [
            'file is broken'
        ]);
        $fileSystemMock = Mockery::mock(Filesystem::class)
            ->shouldReceive('delete')
            ->once()
            ->andThrows($exceptionMock)
            ->getMock();

        $file = new File($filename, $fileSystemMock);

        $result = $file->delete();

        $this->assertEquals('file is broken', $file->getError());
        $this->assertFalse($result);
        $fileSystemMock->shouldHaveReceived('delete')->with([$filename]);
    }
}