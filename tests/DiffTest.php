<?php

namespace Edge\Overseer;

class DiffTest extends \PHPUnit_Framework_TestCase
{
    private $diff;
    private $createdFiles;
    private $nonExistentFile;

    public function setUp()
    {
        $this->diff = new Grep(array());
        $this->createdFiles = array();
        $this->nonExistentFile = __DIR__ . "/non-existent-file";
    }

    public function testEmptyResultWhenNoFileExists()
    {
        assertThat($this->diff->diffFiles($this->nonExistentFile, $this->nonExistentFile), is(emptyString()));
    }

    public function testShouldReturnFileContentWhenBackupDoesNotExist()
    {
        $fileContent = 'hello';
        $currentFile = $this->createFile('current', $fileContent);
        assertThat($this->diff->diffFiles($currentFile, $this->nonExistentFile), is($fileContent));
    }

    private function createFile($filename, $content)
    {
        $path = __DIR__ . "/{$filename}";
        file_put_contents($path, $content);
        $this->createdFiles[] = $path;
        return $path;
    }

    protected function tearDown()
    {
        foreach ($this->createdFiles as $f) {
            unlink($f);
        }
    }
}
