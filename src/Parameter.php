<?php

/**
 * Class Parameter
 */
class Parameter
{

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $default;

    /**
     * @param string $paramStr
     * @return static
     */
    public static function fromString(string $paramStr): self
    {
        $Parameter = new static();
        $parts = explode(" ", trim($paramStr));

        while( ! empty($parts)) {
            $value = $parts[0];
            if(empty($value)) {
                array_shift($parts);
                continue;
            }
            $firstCharacter = $value[0];

            if($firstCharacter === "=") {
                $Parameter->setDefault($parts[1]);
                break;
            }

            switch($firstCharacter) {
                case "$":
                    $Parameter->setName(ltrim($value, "$"));
                    break;
                default:
                    $Parameter->setType($value);
                    break;
            }
            array_shift($parts);
        }
        return $Parameter;
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return implode(" ", array_filter([
            $this->getType() ?: NULL,
            "$".$this->getName(),
            $this->getDefault() !== NULL
                ? " = ".$this->getDefault()
                : NULL
        ]));
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
     * @return string|null
     */
    public function getDefault(): ?string
    {
        return $this->default;
    }

    /**
     * @param string $default
     */
    public function setDefault(string $default): void
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function toHTML(): string
    {
        return implode(" ", array_filter([
            '<span class="param_type">'.$this->getType().'</span>',
            '<span class="param_name">'."$".$this->getName().'</span>',
            $this->getDefault() !== NULL ? " = <span class=\"param_default\">{$this->getDefault()}</span>" : NULL
        ]));
    }

}