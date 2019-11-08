<?php

namespace FileSystem\FileOperation;

use FileSystem\FileSystemException;
use Throwable;

class Read extends FileOperation
{
    private string $filename;

    /**
     * Read constructor.
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
        if(!is_readable($this->filename))
        {
            throw new FileSystemException(sprintf('File \'%s\' is not readable', $this->filename));
        }
    }

    /**
     * @throws FileSystemException
     */
    public function execute(): void
    {
        try
        {
            $this->results = file_get_contents($this->filename);
        }
        catch(Throwable $throwable)
        {
            throw new FileSystemException(sprintf('File \'%s\' is not readable', $this->filename));
        }
    }
}