<?php

class Method 
{
	private $visibility;
	private $isStatic;
	private $name;

    /**
     * @var Parameter[]
     */
	private $parameters = [];

    /**
     * @return mixed
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @param mixed $visibility
     */
    public function setVisibility($visibility): void
    {
        $this->visibility = $visibility;
    }

    /**
     * @return mixed
     */
    public function getisStatic()
    {
        return $this->isStatic;
    }

    /**
     * @param mixed $isStatic
     */
    public function setIsStatic($isStatic): void
    {
        $this->isStatic = $isStatic;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
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
     * @return string
     */
    public function parametersToString(): string
    {
        return implode(", ", array_map(function(Parameter $Parameter) {
            return $Parameter->toString();
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
    public function toString() {
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
    public function toHTML()
    {
        return implode(" ", [
            "<span class='visibility'>".$this->getVisibility().'</span>',
            $this->getisStatic() ? "<span class='static'>static</span>" : "",
            "function",
            "<span class='method_name'>".$this->getName().'</span>',
            "({$this->parametersToHTML()})"
        ]);
    }
}