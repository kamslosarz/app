<?php

namespace File\Filesystem\Type;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;

class YmlFilesystemTest extends TestCase
{
    public function testShouldConstructYmlFilesystem()
    {
        $ymlFilesystem = new YmlFilesystem();

        $this->assertInstanceOf(YmlFilesystem::class, $ymlFilesystem);
    }

    /**
     * @throws ReflectionException
     */
    public function testShouldSaveYmlFileSuccess()
    {
        $fileToSave = FIXTURE_DIR . 'file-yml.yml';
        $fileContent = [
            'test' => [
                'test',
                'test' => [
                    'test',
                    'test',
                    'test',
                    'test',
                    'test' => [
                        'test',
                        'test',
                        'test'
                    ]
                ]
            ]
        ];
        $ymlFilesystem = new YmlFilesystem();

        $saveFileReflection = new ReflectionMethod($ymlFilesystem, 'saveFile');
        $saveFileReflection->setAccessible(true);
        $saveFileReflection->invokeArgs($ymlFilesystem, [
            $fileToSave,
            $fileContent
        ]);
        $ymlFileContents = file_get_contents($fileToSave);
        $yaml = yaml_parse($ymlFileContents);

        $this->deleteFile($fileToSave);
        $this->assertEquals($fileContent, $yaml);
    }

    public function testShouldReadYmlFilesSuccess()
    {
        $file1 = FIXTURE_DIR . 'file-yml.yml';
        file_put_contents($file1, yaml_emit([
            'test' => 123
        ]));
        $file2 = FIXTURE_DIR . 'file2-yml.yml';
        file_put_contents($file2, yaml_emit([
            'test2' => 321
        ]));

        $ymlFilesystem = new YmlFilesystem();
        $filesContents = $ymlFilesystem->read([
            $file1,
            $file2
        ]);
        $this->deleteFile($file1);
        $this->deleteFile($file2);

        $this->assertEquals([
            ['test' => 123],
            ['test2' => 321],
        ], $filesContents);
    }

    private function deleteFile(string $filename): void
    {
        if(file_exists($filename))
        {
            unlink($filename);
        }
    }
}