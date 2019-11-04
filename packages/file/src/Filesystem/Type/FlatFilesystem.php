<?php

namespace File\Filesystem\Type;

use File\Filesystem\Filesystem;
use File\Filesystem\FilesystemException;

class FlatFilesystem extends Filesystem
{
    /**
     * @param string $filename
     * @param $contents
     * @return bool
     * @throws FilesystemException
     */
    protected function saveFile(string $filename, $contents): bool
    {
        if(!file_exists($filename))
        {
            throw new FilesystemException(sprintf('File \'%s\' not exists', $filename));
        }
        if(!is_writable($filename))
        {
            throw new FilesystemException(sprintf('File \'%s\' is not writable', $filename));
        }
        if(($handle = fopen($filename, 'w')) === false)
        {
            throw new FilesystemException(sprintf('Error while opening file \'%s\' for write', $filename));
        }
        if(fputs($handle, $contents) === false)
        {
            throw new FilesystemException(sprintf('Error while saving file \'%s\'', $filename));
        }

        return true;
    }

    /**
     * @param string $filename
     * @return bool
     * @throws FilesystemException
     */
    protected function deleteFile(string $filename): bool
    {
        if(!file_exists($filename))
        {
            throw new FilesystemException(sprintf('File \'%s\' not exits', $filename));
        }

        if(unlink($filename) === false)
        {
            throw new FilesystemException(sprintf('File \'%s\' cannot be delete', $filename));
        }

        return true;
    }

    /**
     * @param string $filename
     * @return string
     * @throws FilesystemException
     */
    protected function readFile(string $filename): string
    {
        if(!file_exists($filename))
        {
            throw new FilesystemException(sprintf('File \'%s\' not exists', $filename));
        }
        if(!is_readable($filename))
        {
            throw new FilesystemException(sprintf('File \'%s\' is not readable', $filename));
        }
        if(($handle = fopen($filename, 'r')) === false)
        {
            throw new FilesystemException(sprintf('Error while opening file \'%s\' for reading', $filename));
        }
        if(($contents = fread($handle)) === false)
        {
            throw new FilesystemException(sprintf('Error while reading file \'%s\'', $filename));
        }

        return $contents;
    }
}