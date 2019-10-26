<?php

/**
 * Class ClassParameter
 */
class ClassParameter extends Parameter
{

    /**
     * @var string|null
     */
    private $visibility;

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
     * @return string
     */
    public function toHTML(): string
    {
        return implode(" ", array_filter([
            '<span class="param_visibility">'.$this->getVisibility().'</span>',
            '<span class="param_type">'.$this->getType().'</span>',
            '<span class="param_name">'."$".$this->getName().'</span>',
            ' = <span class="param_default">' . ($this->getDefault() !== NULL ? "{$this->getDefault()}" : 'NULL') . '</span>',
        ]));
    }
}