<?php

namespace FileSystem\FileOperation;

use FileSystem\FileSystemException;
use Throwable;

class Remove extends FileOperation
{
    private string $filename;

    /**
     * Delete constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @throws FileSystemException
     */
    public function validate(): void
    {
        if(!is_writeable($this->filename))
        {
            throw new FileSystemException(sprintf('File \'%s\' is not deletable', $this->filename));
        }
    }

    /**
     * @throws FileSystemException
     */
    public function execute(): void
    {
        try
        {
            $this->results = unlink($this->filename);
        }
        catch(Throwable $throwable)
        {
            throw new FileSystemException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }
}