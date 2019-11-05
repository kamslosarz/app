<?php

use File\Filesystem\FilesystemException;
use File\Filesystem\Type\FlatFilesystem;
use PHPUnit\Framework\TestCase;

class FlatFilesystemTest extends TestCase
{
    public function testShouldConstructFlatFileSystem()
    {
        $flatFileSystem = new FlatFilesystem();

        $this->assertInstanceOf(FlatFilesystem::class, $flatFileSystem);
    }

    /**
     * @param string $filename
     * @param string $exceptionMessage
     * @param array $fileOperations
     * @param array $filesToDeleteAfterTest
     * @throws FilesystemException
     * @throws ReflectionException
     * @dataProvider saveFileFailed
     */
    public function testShouldSaveFileFailed(string $filename,
        string $exceptionMessage,
        array $fileOperations,
        array $filesToDeleteAfterTest = [])
    {
        $this->expectException(FilesystemException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $flatFileSystem = Mockery::mock(FlatFilesystem::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        if(!empty($fileOperations))
        {
            $flatFileSystem->shouldReceive($fileOperations)
                ->getMock();
        }

        try
        {
            $saveFileReflection = new ReflectionMethod($flatFileSystem, 'saveFile');
            $saveFileReflection->setAccessible(true);
            $saveFileReflection->invokeArgs($flatFileSystem, [$filename, '']);
        }
        catch(FilesystemException $filesystemException)
        {
            $this->deleteFiles($filesToDeleteAfterTest);

            throw $filesystemException;
        }
    }

    public function saveFileFailed()
    {
        return [
            'not writable' => (function () {
                $tmpFile = FIXTURE_DIR . 'tmp-not-writeable-file.txt';
                touch($tmpFile);
                chmod($tmpFile, 0444);

                return [
                    $tmpFile,
                    sprintf('File \'%s\' is not writable', $tmpFile),
                    [],
                    [$tmpFile]
                ];
            })($this),
            'opening error' => (function () {
                $tmpFile = FIXTURE_DIR . 'error-while-opening-file.txt';

                return [
                    $tmpFile,
                    sprintf('Error while opening file \'%s\' for write', $tmpFile),
                    [
                        'fopen' => false,
                    ],
                    [$tmpFile]
                ];
            })($this),
            'saving error' => (function () {
                $tmpFile = FIXTURE_DIR . 'error-while-saving-file.txt';
                touch($tmpFile);

                return [
                    $tmpFile,
                    sprintf('Error while saving file \'%s\'', $tmpFile),
                    [
                        'fopen' => true,
                        'fputs' => false,
                    ],
                    [$tmpFile]
                ];
            })($this),
        ];
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldSaveFileSuccess()
    {
        $fileToSave = FIXTURE_DIR . 'some-file.txt';
        touch($fileToSave);
        $fileToSaveContent = 'some content';
        $flatFileSystem = new FlatFilesystem();

        $saveFileReflection = new ReflectionMethod($flatFileSystem, 'saveFile');
        $saveFileReflection->setAccessible(true);
        $saveFileReflection->invokeArgs($flatFileSystem, [$fileToSave, $fileToSaveContent]);

        $this->assertEquals(file_get_contents($fileToSave), 'some content');

        $this->deleteFiles([$fileToSave]);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldDeleteFileSuccess()
    {
        $filename = FIXTURE_DIR . '/file-to-delete.txt';
        touch($filename);

        $flatFileSystem = new FlatFilesystem();

        $this->assertTrue(file_exists($filename));

        $deleteFileReflection = new ReflectionMethod($flatFileSystem, 'deleteFile');
        $deleteFileReflection->setAccessible(true);
        $deleteFileReflection->invokeArgs($flatFileSystem, [$filename, '']);

        $this->assertFalse(file_exists($filename));
        $this->deleteFiles([$filename]);
    }

    /**
     * @param string $filename
     * @param string $exceptionMessage
     * @param array $fileOperations
     * @throws ReflectionException
     * @dataProvider deleteFileFailed
     */
    public function testShouldDeleteFileFailed(string $filename, string $exceptionMessage, array $fileOperations)
    {
        $this->expectException(FilesystemException::class);
        $this->expectExceptionMessage($exceptionMessage);

        if(!empty($fileOperations))
        {
            $flatFileSystem = Mockery::mock(FlatFilesystem::class)
                ->makePartial()
                ->shouldAllowMockingProtectedMethods()
                ->shouldReceive($fileOperations)
                ->getMock();
        }
        else
        {
            $flatFileSystem = new FlatFilesystem();
        }

        $deleteFileReflection = new ReflectionMethod($flatFileSystem, 'deleteFile');
        $deleteFileReflection->setAccessible(true);
        $deleteFileReflection->invokeArgs($flatFileSystem, [$filename]);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldReadFileSuccess()
    {
        $filename = FIXTURE_DIR . 'some-file-to-read.txt';
        $contents = 'some file contents';
        touch($filename);
        file_put_contents($filename, $contents);

        $flatFileSystem = new FlatFilesystem();

        $readFileReflection = new ReflectionMethod($flatFileSystem, 'readFile');
        $readFileReflection->setAccessible(true);
        $result = $readFileReflection->invokeArgs($flatFileSystem, [$filename]);

        $this->assertEquals($contents, $result);
        $this->deleteFiles([$filename]);
    }


    /**
     * @param string $filename
     * @param string $exceptionMessage
     * @param array $fileOperations
     * @param array $filesToDelete
     * @throws FilesystemException
     * @throws ReflectionException
     * @dataProvider readFileFailed
     */
    public function testShouldReadFileFailed(string $filename,
        string $exceptionMessage,
        array $fileOperations = [],
        array $filesToDelete = [])
    {
        $this->expectException(FilesystemException::class);
        $this->expectExceptionMessage($exceptionMessage);

        if(!empty($fileOperations))
        {
            $flatFileSystem = Mockery::mock(FlatFilesystem::class)
                ->makePartial()
                ->shouldAllowMockingProtectedMethods()
                ->shouldReceive($fileOperations)
                ->getMock();
        }
        else
        {
            $flatFileSystem = new FlatFilesystem();
        }

        try
        {
            $readFileReflection = new ReflectionMethod($flatFileSystem, 'readFile');
            $readFileReflection->setAccessible(true);
            $readFileReflection->invokeArgs($flatFileSystem, [$filename]);
        }
        catch(FilesystemException $filesystemException)
        {
            $this->deleteFiles($filesToDelete);

            throw $filesystemException;
        }
    }

    public function readFileFailed()
    {
        return [
            'file not exists' => (function () {
                $fileToRead = FIXTURE_DIR . 'some-file-to-read-that-not-exists.txt';

                return [
                    $fileToRead,
                    sprintf('File \'%s\' not exists', $fileToRead)
                ];

            })($this),
            'file not readable' => (function () {
                $fileToRead = FIXTURE_DIR . 'some-not-readable-file.txt';
                touch($fileToRead);
                chmod($fileToRead, 0222);

                return [
                    $fileToRead,
                    sprintf('File \'%s\' is not readable', $fileToRead),
                    [],
                    [$fileToRead]
                ];
            })($this),
            'opening file error' => (function () {
                $fileToRead = FIXTURE_DIR . 'some-file-with-error-on-opening.txt';
                touch($fileToRead);
                chmod($fileToRead, 0777);

                return [
                    $fileToRead,
                    sprintf('Error while opening file \'%s\' for reading', $fileToRead),
                    [
                        'fopen' => false
                    ], [$fileToRead]
                ];
            })($this),
            'reading file error' => (function () {
                $fileToRead = FIXTURE_DIR . 'some-file-with-error-on-reading.txt';
                touch($fileToRead);
                chmod($fileToRead, 0777);

                return [
                    $fileToRead,
                    sprintf('Error while reading file \'%s\'', $fileToRead),
                    [
                        'fopen' => true,
                        'fread' => false,
                    ], [$fileToRead]
                ];
            })($this)
        ];
    }

    public function deleteFileFailed()
    {
        return [
            'case file not exists' => [
                'some-file-that-not-exists.txt',
                'File \'some-file-that-not-exists.txt\' not exits',
                []
            ],
            'case unlink failed' => (function () {
                $filename = 'some-file-that-exists.txt';

                return [
                    $filename,
                    sprintf('File \'%s\' not exits', $filename),
                    [
                        'unlink' => false
                    ]
                ];
            })($this)
        ];
    }

    private function deleteFiles(array $filesToDeleteAfterTest): void
    {
        foreach($filesToDeleteAfterTest as $file)
        {
            if(file_exists($file))
            {
                unlink($file);
            }
        }
    }
}