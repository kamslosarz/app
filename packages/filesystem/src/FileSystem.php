<?php

namespace FileSystem;

use FileSystem\FileOperation\FileOperation;
use FileSystem\FileOperation\Read;
use FileSystem\FileOperation\Remove;
use FileSystem\FileOperation\Save;

class FileSystem
{
    /**
     * @param string $filename
     * @param string $contents
     * @return bool
     * @throws FileSystemException
     */
    public function save(string $filename, string $contents): bool
    {
        return $this->execute(Save::class, [$filename, $contents]);
    }

    /**
     * @param $filename
     * @return string
     * @throws FileSystemException
     */
    public function read(string $filename): string
    {
        return $this->execute(Read::class, [$filename]);
    }

    /**
     * @param string $filename
     * @return bool
     * @throws FileSystemException
     */
    public function remove(string $filename): bool
    {
        return $this->execute(Remove::class, [$filename]);
    }

    /**
     * @param string $operationClassname
     * @param $parameters
     * @return string
     * @throws FileSystemException
     */
    protected function execute(string $operationClassname, $parameters)
    {
        /** @var FileOperation $fileOperation */
        $fileOperation = new $operationClassname(...$parameters);
        $fileOperation->validate();
        $fileOperation->execute();

        return $fileOperation->getResults();
    }
}