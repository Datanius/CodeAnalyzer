<?php

class CodeFile
{
    /**
     * @var CodeClass|null
     */
    private $CodeClass;

    /**
     * @param SplFileInfo $fileInfo
     * @return CodeFile
     */
    public static function fromFileInfo(SplFileInfo $fileInfo): CodeFile
    {
        $CodeFile = new CodeFile();
        $fileContent = $CodeFile->removeCommentsFromContent(
            file_get_contents($fileInfo->getRealPath())
        );
        try {
            $CodeFile->CodeClass = CodeClass::fromFileContent($fileContent);
        } catch (Exception $e) {}
        return $CodeFile;
    }

    /**
     * @return CodeClass|null
     */
    public function getCodeClass(): ?CodeClass
    {
        return $this->CodeClass ?? null;
    }

    /**
     * @param string $content
     * @return string
     */
    private function removeCommentsFromContent(string $content): string
    {
        $replacedContent = preg_replace("/\/\*\*(.*)\*\//sU", "", $content);
        return $replacedContent ?? $content;
    }
}