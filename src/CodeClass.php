<?php

/**
 * Class CodeClass
 */
class CodeClass
{
    /**
     * @var array
     */
    private $methods = [];

    /**
     * @var ClassParameter[]
     */
    private $parameters = [];

    /**
     * @var string
     */
    private $name = "";

    /**
     * @var string
     */
    private $namespace = "";

    /**
     * @var int
     */
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
        $CodeClass->namespace = $CodeClass->extractNamespace($fileContent);
        $CodeClass->methods = $CodeClass->extractMethods($fileContent);
        $CodeClass->parameters = $CodeClass->extractClassParameters($fileContent);
        $CodeClass->lines = $CodeClass->countLines($fileContent);
        return $CodeClass;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return "{$this->getNamespace()}{$this->getName()} ({$this->getMethodCount()} method" . ($this->getMethodCount() === 1 ? '' : 's') . ") {$this->getLines()} line" . ($this->getLines() === 1 ? '' : 's');
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return ClassParameter[]
     */
    public function getClassParameters(): array
    {
        return $this->parameters;
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
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace ? $this->namespace . "\\" : "";
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
        $pattern = "/class\s+(?<classname>\w+)/";
        preg_match($pattern, $fileContent, $matches);

        if( ! isset($matches["classname"])) {
            throw new Exception("File does not contain a class.");
        }

        return $matches["classname"] ?? "";
    }

    /**
     * @param string $fileContent
     * @return string
     */
    private function extractNamespace(string $fileContent): string
    {
        $pattern = "/^\s*namespace\s+(?<namespace>.*);/m";
        preg_match($pattern, $fileContent, $matches);

        if( ! isset($matches["namespace"])) {
            return "";
        }
        return $matches["namespace"] ?? "";
    }

    /**
     * @param string $fileContent
     * @return array
     */
    private function extractClassParameters(string $fileContent): array
    {
        $pattern = "/(?<visibility>public|private|protected)\s+(?<param>\\$[a-zA-Z]+[a-zA-Z0-9]*\s*.*?);/"; // \s+(?<param>$[a-zA-Z]+[a-zA-Z0-9]*)/
        preg_match_all($pattern, $fileContent, $matches);
        $parameters = [];
        foreach($matches[0] as $index => $match) {
            $Parameter = ClassParameter::fromString($matches["param"][$index]);
            $Parameter->setVisibility($matches["visibility"][$index]);
            $parameters[] = $Parameter;
        }
        return $parameters;
    }

    /**
     * @param string $fileContent
     * @return Method[]
     */
    private function extractMethods(string $fileContent): array
    {
        $pattern = "/(?<visibility>public|private|protected)?\s+(?<isStatic>static )*function\s+(?<name>\w+)\((?<params>.*)\)\s*(?<returnType>:\s*[\\a-zA-Z0-9]+)*/";
        preg_match_all($pattern, $fileContent, $matches);
        $methods = [];
        foreach($matches[0] as $index => $match) {
            $Method = new Method();
            $Method->setVisibility($matches["visibility"][$index] ?: "public");
            $Method->setName($matches["name"][$index]);
            $Method->setIsStatic(trim($matches["isStatic"][$index]) !== "");
            $Method->setParameters($this->extractParameters($matches["params"][$index]));
            $Method->setReturnType(trim($matches["returnType"][$index]));
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