<?php

/**
 * Class Method
 */
class Method 
{

    /**
     * @var string|null
     */
	private $visibility;

    /**
     * @var bool
     */
	private $isStatic = false;

    /**
     * @var string|null
     */
	private $name;

    /**
     * @var string|null
     */
	private $returnType;

    /**
     * @var Parameter[]
     */
	private $parameters = [];

    /**
     * @return string|null
     */
    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    /**
     * @param string $visibility
     */
    public function setVisibility(string $visibility): void
    {
        $this->visibility = $visibility;
    }

    /**
     * @return bool
     */
    public function getisStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * @param bool $isStatic
     */
    public function setIsStatic(bool $isStatic): void
    {
        $this->isStatic = $isStatic;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param Parameter[] $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @param string $type
     */
    public function setReturnType(string $type): void
    {
        $this->returnType = $type;
    }

    /**
     * @return string|null
     */
    public function getReturnType(): ?string
    {
        return $this->returnType;
    }

    /**
     * @return string
     */
    public function parametersToString(): string
    {
        return implode(", ", array_map(function(Parameter $Argument) {
            return implode(" ", array_filter([
                $Argument->getType(),
                "$".$Argument->getName(),
                $Argument->getDefault() !== NULL ? " = ".$Argument->getDefault() : NULL
            ]));
        }, $this->parameters));
    }

    /**
     * @return string
     */
    public function parametersToHTML(): string
    {
        return implode(", ", array_map(function(Parameter $Parameter) {
            return $Parameter->toHTML();
        }, $this->parameters));
    }

    /**
     * @return string
     */
    public function returnToHTML(): string
    {
        if(!$this->getReturnType()) {
            return '';
        }
        return "<span class='method_return'>" . $this->getReturnType() . "</span>";
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode(" ", [
            $this->getVisibility(),
            $this->getisStatic() ? "static" : "",
            $this->getName(),
            "({$this->parametersToString()})"
        ]);
    }

    /**
     * @return string
     */
    public function toHTML(): string
    {
        return implode(" ", [
            "<span class='visibility'>".$this->getVisibility().'</span>',
            $this->getisStatic() ? "<span class='static'>static</span>" : "",
            "function",
            "<span class='method_name'>".$this->getName().'</span>',
            "({$this->parametersToHTML()})",
            "{$this->returnToHTML()}"
        ]);
    }
}