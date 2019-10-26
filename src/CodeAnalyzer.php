<?php

/**
 * Class CodeAnalyzer
 */
class CodeAnalyzer
{

    /**
     * @var array
     */
    private $codeFiles = [];

    const ALLOWED_EXTENSIONS = ["php"];

    /**
     * @param string $filePath
     * @return CodeAnalyzer
     */
    public static function analyzePath(string $filePath): CodeAnalyzer
    {
        $CodeAnalyzer = new CodeAnalyzer();

        $files = array_filter($CodeAnalyzer->getAllFilesFromPath($filePath), function(\SplFileInfo $file) {
            return in_array($file->getExtension(), self::ALLOWED_EXTENSIONS);
        });

        array_walk($files, function(\SplFileInfo $file) use ($CodeAnalyzer) {
            $CodeAnalyzer->codeFiles[] = CodeFile::fromFileInfo($file);
        });

        return $CodeAnalyzer;
    }

    /**
     * @return CodeFile[]
     */
    public function getCodeFiles(): array
    {
        return $this->codeFiles;
    }

    /**
     * @return CodeClass[]
     */
    public function getCodeClasses(): array
    {
        return array_filter(array_map(function(CodeFile $CodeFile) {
            return $CodeFile->getCodeClass();
        }, $this->getCodeFiles()));
    }

    /**
     * @param string $filePath
     * @return \SplFileInfo[]
     */
    private function getAllFilesFromPath(string $filePath): array
    {
        $files = [];
        foreach((new DirectoryIterator($filePath)) as $file) {
            if( ! $file->isFile()) {
                continue;
            }
            $files[] = new \SplFileInfo($file->getPathname());
        }
        return $files;
    }
}