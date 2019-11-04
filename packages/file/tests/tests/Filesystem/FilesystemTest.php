<?php


namespace tests\Filesystem;

use File\Filesystem\Filesystem;
use Mockery;
use Mockery\Exception;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testShouldSaveFilesSuccess()
    {
        $filesToSave = [
            ['file1.txt', 'file1 content'],
            ['file2.txt', 'file2 content'],
            ['file3.txt', 'file3 content']
        ];

        /** @var Filesystem $fileSystem */
        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('saveFile')
            ->andReturnValues([true, true, true])
            ->getMock();

        $fileSystem->save($filesToSave);

        /** @var Mockery\MockInterface $fileSystem */
        $fileSystem->shouldHaveReceived('saveFile')->times(3);
    }

    public function testShouldSaveFilesFailed()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('error occurred');
        $exceptionMock = Mockery::mock(Exception::class, [
            'error occurred'
        ]);

        $filesToSave = [
            ['file1.txt', 'file1 content'],
            ['file2.txt', 'file2 content'],
            ['file3.txt', 'file3 content']
        ];

        /** @var Filesystem $fileSystem */
        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('saveFile')
            ->andThrows($exceptionMock)
            ->getMock();

        $fileSystem->save($filesToSave);
    }

    /**
     * @throws \Exception
     */
    public function testShouldSaveFirstFileAndThrowExceptionOnSecond()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('error occurred');
        $exceptionMock = Mockery::mock(Exception::class, [
            'error occurred'
        ]);

        $filesToSave = [
            ['file1.txt', 'file1 content'],
            ['file2.txt', 'file2 content'],
        ];

        /** @var Filesystem $fileSystem */
        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('saveFile')
            ->with('file1.txt', 'file1 content')
            ->getMock()
            ->shouldReceive('saveFile')
            ->with('file2.txt', 'file2 content')
            ->andThrows($exceptionMock)
            ->getMock();

        try
        {

            $fileSystem->save($filesToSave);
        }
        catch(\Exception $exception)
        {
            /** @var Mockery\MockInterface $fileSystem */
            $fileSystem->shouldHaveReceived('saveFile')->with('file1.txt', 'file1 content')->once();
            $fileSystem->shouldHaveReceived('saveFile')->with('file2.txt', 'file2 content')->once();

            throw $exception;
        }

    }

    public function testShouldReadFilesSuccess()
    {
        $filesToRead = [
            'file1.txt',
            'file2.log'
        ];

        /** @var Filesystem $fileSystem */
        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('readFile')
            ->with('file1.txt')
            ->andReturn('file1.txt content')
            ->getMock()
            ->shouldReceive('readFile')
            ->with('file2.log')
            ->andReturn('file2.log content')
            ->getMock();

        $result = $fileSystem->read($filesToRead);

        $this->assertEquals(['file1.txt content', 'file2.log content'], $result);
    }

    /**
     * @throws \Exception
     */
    public function testShouldReadFilesFailed()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('error occurred');
        $filesToRead = [
            'file1.txt',
            'file2.log'
        ];
        $exceptionMock = Mockery::mock(Exception::class, [
            'error occurred'
        ]);

        /** @var Filesystem $fileSystem */
        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('readFile')
            ->once()
            ->with('file1.txt')
            ->andThrows($exceptionMock)
            ->getMock();

        try
        {
            $fileSystem->read($filesToRead);
        }
        catch(\Exception $exception)
        {
            /** @var Mockery\MockInterface $fileSystem */
            $fileSystem->shouldHaveReceived('readFile')->once();

            throw $exception;
        }
    }

    /**
     * @throws \Exception
     */
    public function testShouldReadFirstFileSuccessAndThrowExceptionOnSecond()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('error occurred');
        $filesToRead = [
            'file1.txt',
            'file2.log'
        ];
        $exceptionMock = Mockery::mock(Exception::class, [
            'error occurred'
        ]);
        /** @var Filesystem $fileSystem */
        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('readFile')
            ->once()
            ->with('file1.txt')
            ->andReturn('file1 contents')
            ->getMock()
            ->shouldReceive('readFile')
            ->once()
            ->with('file2.log')
            ->andThrows($exceptionMock)
            ->getMock();

        try
        {
            $fileSystem->read($filesToRead);
        }
        catch(\Exception $exception)
        {
            /** @var Mockery\MockInterface $fileSystem */
            $fileSystem->shouldHaveReceived('readFile')->with('file1.txt')->once();
            $fileSystem->shouldHaveReceived('readFile')->with('file2.log')->once();

            throw $exception;
        }
    }


    /**
     * @doesNotPerformAssertions
     */
    public function testShouldDeleteFilesSuccess()
    {
        $filesToDelete = [
            'file1',
            'file2.txt'
        ];

        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('deleteFile')
            ->twice()
            ->andReturnTrue()
            ->getMock();

        /** @var Filesystem $fileSystem */
        $fileSystem->delete($filesToDelete);

        $fileSystem->shouldHaveReceived('deleteFile')->with('file1')->once();
        $fileSystem->shouldHaveReceived('deleteFile')->with('file2.txt')->once();
    }

    /**
     * @throws \Exception
     */
    public function testShouldDeleteFilesFailed()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('error occurred');

        $exceptionMock = Mockery::mock(\Exception::class, [
            'error occurred'
        ]);
        $filesToDelete = [
            'file1',
            'file2.txt'
        ];

        /** @var Filesystem $fileSystem */
        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('deleteFile')
            ->with('file1')
            ->once()
            ->andThrows($exceptionMock)
            ->getMock();

        try
        {
            $fileSystem->delete($filesToDelete);
        }
        catch(\Exception $exception)
        {
            /** @var Mockery\MockInterface $fileSystem */
            $fileSystem->shouldHaveReceived('deleteFile')->with('file1')->once();

            throw $exception;

        }
    }

    /**
     * @throws \Exception
     */
    public function testShouldDeleteFirstFileButThrowsExceptionOnSecond()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('error occurred');
        $exceptionMock = Mockery::mock(\Exception::class, [
            'error occurred'
        ]);
        $filesToDelete = [
            'file1',
            'file2.txt'
        ];

        /** @var Filesystem $fileSystem */
        $fileSystem = Mockery::mock(Filesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('deleteFile')
            ->once()
            ->with('file1')
            ->getMock()
            ->shouldReceive('deleteFile')
            ->once()
            ->with('file2.txt')
            ->andThrows($exceptionMock)
            ->getMock();

        try
        {
            $fileSystem->delete($filesToDelete);
        }
        catch(\Exception $exception)
        {
            /** @var Mockery\MockInterface $fileSystem */
            $fileSystem->shouldHaveReceived('deleteFile')->with('file1')->once();
            $fileSystem->shouldHaveReceived('deleteFile')->with('file2.txt')->once();

            throw $exception;
        }
    }
}