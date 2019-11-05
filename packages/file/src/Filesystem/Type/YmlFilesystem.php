<?php


namespace File\Filesystem\Type;


class YmlFilesystem extends FlatFilesystem
{
    protected function saveFile(string $filename, $contents): bool
    {
        $contents = yaml_emit($contents);

        return parent::saveFile($filename, $contents);
    }

    public function read(array $files)
    {
        $filesContents = parent::read($files);

        if(!empty($filesContents))
        {
            foreach($filesContents as &$fileContents)
            {
                $fileContents = yaml_parse($fileContents);
            }
        }

        return $filesContents;
    }
}