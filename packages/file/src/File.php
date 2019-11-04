<?php

namespace File;

use File\Filesystem\FilesystemException;
use File\Filesystem\FilesystemInterface;

class File
{
    /** @var string $filename */
    protected string $filename;
    /** @var */
    protected FilesystemInterface $filesystem;
    /** @var string */
    protected string $error;

    public function __construct(string $filename, FilesystemInterface $filesystem)
    {
        $this->filename = $filename;
        $this->filesystem = $filesystem;
    }

    /**
     * @return bool
     */
    public function save(): bool
    {
        try
        {
            $this->filesystem->save([$this->filename]);
        }
        catch(FilesystemException $exception)
        {
            $this->error = $exception->getMessage();

            return false;
        }

        return true;
    }

    public function read()
    {
        try
        {
            return $this->filesystem->read([$this->filename]);
        }
        catch(FilesystemException $exception)
        {
            $this->error = $exception->getMessage();

            return false;
        }
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        try
        {
            $this->filesystem->delete([$this->filename]);
        }
        catch(FilesystemException $exception)
        {
            $this->error = $exception->getMessage();

            return false;
        }

        return true;
    }

    public function getError(): string
    {
        return $this->error;
    }
}