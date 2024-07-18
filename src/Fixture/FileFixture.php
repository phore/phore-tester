<?php

namespace Phore\Tester\Fixture;

class FileFixture
{
    public readonly string $basePath;

    public function __construct(string $basePath, public readonly ?string $sampleDir = null)
    {
        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if ($this->sampleDir && ! is_dir($this->sampleDir))
            throw new InvalidArgumentException("Sample directory '$this->sampleDir' not found.");
        
    }

    public function createFile(string $relativePath, string $content = ''): string
    {
        $fullPath = $this->basePath . $relativePath;
        $dirPath = dirname($fullPath);
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        file_put_contents($fullPath, $content);
        return $fullPath;
    }

    public function createDirectory(string $relativePath): string
    {
        $fullPath = $this->basePath . $relativePath;
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0777, true);
        }
        return $fullPath;
    }

    public function deletePath(string $relativePath): self
    {
        $fullPath = $this->basePath . $relativePath;
        if (is_dir($fullPath)) {
            $this->deleteDirectory($fullPath);
        } elseif (is_file($fullPath)) {
            unlink($fullPath);
        }
        return $this;
    }

    private function deleteDirectory(string $dirPath): self
    {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dirPath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileInfo) {
            $filePath = $fileInfo->getRealPath();
            if ($fileInfo->isDir()) {
                rmdir($filePath);
            } else {
                unlink($filePath);
            }
        }
        rmdir($dirPath);
        return $this;
    }

    public function copyFromSampleDir(string $sampleDir = null): self
    {
        if (! $sampleDir)
            $sampleDir = $this->sampleDir;
        $this->recursiveCopy($sampleDir, $this->basePath);
        return $this;
    }

    private function recursiveCopy(string $source, string $dest): void
    {
        $dir = opendir($source);
        @mkdir($dest);
        while (($file = readdir($dir)) !== false) {
            if ($file !== '.' && $file !== '..') {
                $srcPath = $source . DIRECTORY_SEPARATOR . $file;
                $destPath = $dest . DIRECTORY_SEPARATOR . $file;
                if (is_dir($srcPath)) {
                    $this->recursiveCopy($srcPath, $destPath);
                } else {
                    copy($srcPath, $destPath);
                }
            }
        }
        closedir($dir);
    }

    public function clean(bool $copyFromSampleDir = true): self
    {
        $this->deleteDirectory($this->basePath);
        mkdir($this->basePath, 0777, true);
        if ($this->sampleDir && $copyFromSampleDir)
            $this->copyFromSampleDir($this->sampleDir);
        return $this;
    }
}