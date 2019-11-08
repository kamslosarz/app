<?php

namespace FileSystem\File;

use FileSystem\FileSystem;
use FileSystem\FileSystemException;

class File
{
    protected string $filename = '';
    protected string $error = '';
    private FileSystem $fileSystem;

    /**
     * File constructor.
     * @param string $filename
     * @param FileSystem $fileSystem
     */
    public function __construct(string $filename, FileSystem $fileSystem)
    {
        $this->filename = $filename;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param string $contents
     * @return bool
     */
    public function save(string $contents): bool
    {
        try
        {
            return $this->fileSystem->save($this->filename, $contents);
        }
        catch(FileSystemException $fileSystemException)
        {
            $this->error = $fileSystemException->getMessage();

            return false;
        }
    }

    /**
     * @return string
     */
    public function read(): string
    {
        try
        {
            return $this->fileSystem->read($this->filename);
        }
        catch(FileSystemException $fileSystemException)
        {
            $this->error = $fileSystemException->getMessage();

            return false;
        }
    }

    /**
     * @return bool
     */
    public function remove(): bool
    {
        try
        {
            return $this->fileSystem->remove($this->filename);
        }
        catch(FileSystemException $fileSystemException)
        {
            $this->error = $fileSystemException->getMessage();

            return false;
        }
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }
}