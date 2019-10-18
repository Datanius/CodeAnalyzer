<?php

class CodeClass
{
    private $methods = [];
    private $name = "";
    private $lines = 0;

    /**
     * @param string $fileContent
     * @return CodeClass
     * @throws Exception
     */
    public static function fromFileContent(string $fileContent): CodeClass
    {
        $CodeClass = new CodeClass();
        $CodeClass->name = $CodeClass->extractClassName($fileContent);
        $CodeClass->methods = $CodeClass->extractMethods($fileContent);
        $CodeClass->lines = $CodeClass->countLines($fileContent);
        return $CodeClass;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        $methodCount = count($this->methods);
        return "{$this->getName()} ({$methodCount} methods) {$this->getLines()} lines";
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return int
     */
    public function getMethodCount(): int
    {
        return count($this->methods);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getLines(): int
    {
        return $this->lines;
    }

    /**
     * @param string $fileContent
     * @return string
     * @throws Exception
     */
    private function extractClassName(string $fileContent): string
    {
        $pattern = "/class (?<classname>\w+)/";
        preg_match($pattern, $fileContent, $matches);

        if( ! isset($matches["classname"])) {
            throw new Exception("File does not contain a class.");
        }

        return $matches["classname"] ?? "";
    }

    /**
     * @param string $fileContent
     * @return Method[]
     */
    private function extractMethods(string $fileContent): array
    {
        $pattern = "/(?<visibility>public|private|protected)? (?<isStatic>static )*function (?<name>\w+)\((?<params>.*)\)/";
        preg_match_all($pattern, $fileContent, $matches);
        $methods = [];
        foreach($matches[0] as $index => $match) {
            $Method = new Method();
            $Method->setVisibility($matches["visibility"][$index] ?: "public");
            $Method->setName($matches["name"][$index]);
            $Method->setIsStatic(trim($matches["isStatic"][$index]) !== "");
            $Method->setParameters($this->extractParameters($matches["params"][$index]));
            $methods[] = $Method;
        }
        return $methods;
    }

    /**
     * @param string $parameterStr
     * @return array
     */
    private function extractParameters(string $parameterStr): array
    {
        $parameterStr = trim($parameterStr);

        if(empty($parameterStr)) {
            return [];
        }
        $parts = explode(",", $parameterStr);

        $parts = array_filter($parts, function($parameter) {
            return ! (trim($parameter) === "");
        });

        return array_map(function($parameter) {
            return Parameter::fromString($parameter);
        }, $parts);
    }

    /**
     * @param string $fileContent
     * @return int
     */
    private function countLines(string $fileContent): int
    {
        return count(explode(PHP_EOL, $fileContent));
    }
}