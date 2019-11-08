<?php

namespace FileSystem\FileOperation;

use FileSystem\FileSystemException;
use Throwable;

class Save extends FileOperation
{
    private string $filename;
    private string $contents;

    /**
     * Save constructor.
     * @param string $filename
     * @param string $contents
     */
    public function __construct(string $filename, string $contents)
    {
        $this->filename = $filename;
        $this->contents = $contents;
    }

    /**
     * @throws FileSystemException
     */
    public function validate(): void
    {
        if(!is_writeable(dirname($this->filename)))
        {
            throw new FileSystemException(sprintf('File \'%s\' in not writeable', $this->filename));
        }

        if(file_exists($this->filename) && !is_writeable($this->filename))
        {
            throw new FileSystemException(sprintf('File \'%s\' in not writeable', $this->filename));
        }
    }

    /**
     * @throws FileSystemException
     */
    public function execute(): void
    {
        try
        {
            $this->results = file_put_contents($this->filename, $this->contents);
        }
        catch(Throwable $throwable)
        {
            throw new FileSystemException($throwable->getMessage(), $throwable->getCode(), $throwable);
        }
    }
}