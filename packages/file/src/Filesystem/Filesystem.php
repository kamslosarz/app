<?php

namespace File\Filesystem;

abstract class Filesystem implements FilesystemInterface
{
    /**
     * @param array $files
     */
    public function save(array $files): void
    {
        foreach($files as $file)
        {
            list($filename, $contents) = $file;

            $this->saveFile($filename, $contents);
        }
    }

    /**
     * @param array $files
     * @return array
     */
    public function read(array $files)
    {
        $contents = [];
        foreach($files as $filename)
        {
            $contents[] = $this->readFile($filename);
        }

        return $contents;
    }

    /**
     * @param array $files
     */
    public function delete(array $files): void
    {
        foreach($files as $filename)
        {
            $this->deleteFile($filename);
        }
    }

    abstract protected function saveFile(string $filename, $contents): bool;

    abstract protected function deleteFile(string $filename): bool;

    abstract protected function readFile(string $filename);
}