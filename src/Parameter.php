<?php

class Parameter
{
    private $type;
    private $name;
    private $default;

    /**
     * @param string $paramStr
     * @return Parameter
     */
    public static function fromString(string $paramStr): Parameter
    {
        $Parameter = new Parameter();
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
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
     * @return string
     */
    public function getDefault()
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
}