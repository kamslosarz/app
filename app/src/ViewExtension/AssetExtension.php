<?php

namespace App\ViewExtension;

use FileSystem\File\File;
use View\ViewExtension\ViewExtension;

class AssetExtension extends ViewExtension
{
    protected function getFunctions(): array
    {
        return [
            'inlineCss' => [
                &$this, 'inlineCss'
            ]
        ];
    }

    public function inlineCss(string $filename): string
    {
        $file = new File($filename);

        return $file->read();
    }
}