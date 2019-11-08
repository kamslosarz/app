<?php

namespace tests;

use FileSystem\FileOperation\Read;
use FileSystem\FileOperation\Remove;
use FileSystem\FileOperation\Save;
use FileSystem\FileSystem;
use FileSystem\FileSystemException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class FileSystemTest extends TestCase
{
    /**
     * @throws FileSystemException
     * @doesNotPerformAssertions
     */
    public function testShouldExecuteSaveFileSuccess()
    {
        $filename = 'filename.txt';
        $contents = 'file contents';

        /** @var FileSystem $fileSystem */
        $fileSystem = Mockery::mock(FileSystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('execute')
            ->once()
            ->andReturnTrue()
            ->getMock();

        $fileSystem->save($filename, $contents);

        /** @var MockInterface $fileSystem */
        $fileSystem->shouldHaveReceived('execute')->once()->with(Save::class, [$filename, $contents]);
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldExecuteSaveFileFailed()
    {
        $filename = 'file.txt';
        $contents = 'file contents';
        $exceptionMessage = 'file cannot be saved';
        $exceptionMock = Mockery::mock(FileSystemException::class, [$exceptionMessage]);

        /** @var FileSystem $fileSystem */
        $fileSystem = Mockery::mock(FileSystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('execute')
            ->once()
            ->andThrows($exceptionMock)
            ->getMock();

        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage($exceptionMessage);

        try
        {
            $fileSystem->save($filename, $contents);
        }
        catch(FileSystemException $fileSystemException)
        {
            /** @var MockInterface $fileSystem */
            $fileSystem->shouldHaveReceived('execute')->with(Save::class, [$filename, $contents])->once();

            throw $fileSystemException;
        }
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldExecuteReadFileSuccess()
    {
        $filename = 'filename.txt';
        $contents = 'file contents';

        /** @var FileSystem $fileSystem */
        $fileSystem = Mockery::mock(FileSystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('execute')
            ->once()
            ->andReturn($contents)
            ->getMock();

        $results = $fileSystem->read($filename);

        /** @var MockInterface $fileSystem */
        $fileSystem->shouldHaveReceived('execute')->once()->with(Read::class, [$filename]);
        $this->assertEquals($results, $contents);
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldExecuteReadFileFailed()
    {
        $filename = 'file.txt';
        $exceptionMessage = 'file cannot be read';
        $exceptionMock = Mockery::mock(FileSystemException::class, [$exceptionMessage]);

        /** @var FileSystem $fileSystem */
        $fileSystem = Mockery::mock(FileSystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('execute')
            ->once()
            ->andThrows($exceptionMock)
            ->getMock();

        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage($exceptionMessage);

        try
        {
            $fileSystem->read($filename);
        }
        catch(FileSystemException $fileSystemException)
        {
            /** @var MockInterface $fileSystem */
            $fileSystem->shouldHaveReceived('execute')->with(Read::class, [$filename])->once();

            throw $fileSystemException;
        }
    }

    /**
     * @throws FileSystemException
     * @doesNotPerformAssertions
     */
    public function testShouldExecuteRemoveFileSuccess()
    {
        $filename = 'filename.txt';

        /** @var FileSystem $fileSystem */
        $fileSystem = Mockery::mock(FileSystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('execute')
            ->once()
            ->andReturnTrue()
            ->getMock();

        $fileSystem->remove($filename);

        /** @var MockInterface $fileSystem */
        $fileSystem->shouldHaveReceived('execute')->once()->with(Remove::class, [$filename]);
    }


    /**
     * @throws FileSystemException
     */
    public function testShouldExecuteRemoveFileFailed()
    {
        $filename = 'file.txt';
        $exceptionMessage = 'file cannot be removed';
        $exceptionMock = Mockery::mock(FileSystemException::class, [$exceptionMessage]);

        /** @var FileSystem $fileSystem */
        $fileSystem = Mockery::mock(FileSystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('execute')
            ->once()
            ->andThrows($exceptionMock)
            ->getMock();

        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage($exceptionMessage);

        try
        {
            $fileSystem->remove($filename);
        }
        catch(FileSystemException $fileSystemException)
        {
            /** @var MockInterface $fileSystem */
            $fileSystem->shouldHaveReceived('execute')->with(Remove::class, [$filename])->once();

            throw $fileSystemException;
        }
    }
}