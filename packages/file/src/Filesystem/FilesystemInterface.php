<?php

namespace File\Filesystem;

interface FilesystemInterface
{
    /**
     * @param array $files
     * @throws FilesystemException
     */
    public function save(array $files): void;

    /**
     * @param array $files
     * @return mixed
     * @throws FilesystemException
     */
    public function read(array $files);

    /**
     * @param array $files
     * @throws FilesystemException
     */
    public function delete(array $files): void;
}