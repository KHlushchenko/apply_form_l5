<?php
namespace Vis\ApplyForms\Models;

class ApplyFormInputCleaner
{
    private $array = [];

    public function setArray(array $array)
    {
        $this->array = $array;
    }

    public function getFromArray(string $field): string
    {
        return $this->array[$field] ?? '';
    }

    public function getIntFromArray(string $field): int
    {
        return (int)$this->getFromArray($field);
    }

    public function getFloatFromArray(string $field): float
    {
        return (float)$this->getFromArray($field);
    }

    public function getStringFromArray(string $field): string
    {
        return (string)$this->getFromArray($field);
    }

    public function getCleanStringFromArray(string $field): string
    {
        return htmlspecialchars(trim($this->getStringFromArray($field)));
    }

}
