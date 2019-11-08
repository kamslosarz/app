<?php

namespace FileSystem\FileOperation;

use FileSystem\FileSystemException;

abstract class FileOperation
{
    protected $results;

    /**
     * @throws FileSystemException
     */
    abstract public function validate(): void;

    /**
     * @throws FileSystemException
     */
    abstract public function execute(): void;

    public function getResults()
    {
        return $this->results;
    }
}