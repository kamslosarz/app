<?php


namespace tests\FileOperation;

use FileSystem\FileOperation\Save;
use FileSystem\FileSystemException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionProperty;

class SaveTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testShouldConstructSaveFileOperationSuccess()
    {
        $filename = 'file.txt';
        $contents = 'file contents';
        $save = new Save($filename, $contents);

        $filenameProperty = new ReflectionProperty($save, 'filename');
        $filenameProperty->setAccessible(true);

        $this->assertEquals($filename, $filenameProperty->getValue($save));
    }

    /**
     * @throws FileSystemException
     * @doesNotPerformAssertions
     */
    public function testShouldValidateSaveFileOperationSuccessWhenFileNotExists()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        $contents = 'file contents';

        $save = new Save($filename, $contents);
        $save->validate();
    }


    /**
     * @throws FileSystemException
     * @doesNotPerformAssertions
     */
    public function testShouldValidateSaveFileOperationSuccessWhenFileExists()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        touch($filename);
        $contents = 'file contents';

        $save = new Save($filename, $contents);
        $save->validate();
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldValidateSaveFileOperationFailedWhenDirectoryIsNotWritable()
    {
        $filename = FIXTURE_DIR . 'tmp-files/not-writeable-directory/file.txt';
        $contents = 'file contents';
        @mkdir(dirname($filename), 0000, true);

        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage(sprintf('File \'%s\' in not writeable', $filename));

        try
        {
            $save = new Save($filename, $contents);
            $save->validate();
        }
        catch(FileSystemException $fileSystemException)
        {
            chmod(dirname($filename), 0777);
            rmdir(dirname($filename));

            throw $fileSystemException;
        }
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldValidateSaveFileOperationFailedWhenFileExistsAndIsNotWritable()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        touch($filename);
        chmod($filename, 0000);
        $contents = '';

        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage(sprintf('File \'%s\' in not writeable', $filename));

        try
        {
            $save = new Save($filename, $contents);
            $save->validate();
        }
        catch(FileSystemException $fileSystemException)
        {
            chmod($filename, 0777);
            unlink($filename);

            throw $fileSystemException;
        }
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldExecuteSaveFileOperationSuccess()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        $contents = 'file contents';

        try
        {
            $save = new Save($filename, $contents);
            $save->execute();
            unlink($filename);
        }
        catch(FileSystemException $fileSystemException)
        {
            @unlink($filename);

            throw $fileSystemException;
        }

        $result = $save->getResults();
        $this->assertEquals(strlen($contents), $result);
    }

    /**
     * @throws FileSystemException
     */
    public function testShouldExecuteSaveFileOperationFailed()
    {
        $filename = FIXTURE_DIR . 'tmp-files/file.txt';
        touch($filename);
        chmod($filename, 0000);
        $contents = 'contents';

        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage(sprintf('file_put_contents(%s): failed to open stream: Permission denied', $filename));

        try
        {
            $save = new Save($filename, $contents);
            $save->execute();
        }
        catch(FileSystemException $fileSystemException)
        {
            chmod($filename, 0777);
            unlink($filename);

            throw $fileSystemException;
        }
    }
}