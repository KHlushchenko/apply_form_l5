<?php
namespace Vis\ApplyForm\Helpers;

/**
 * Class InputCleaner
 * @package Vis\ApplyForm\Helpers
 */
class InputCleaner
{
    /** Defines input array that will be operated on
     * @var array
     */
    private $array = [];

    /** Sets array to $array property
     * @param array $array
     */
    public function setArray(array $array)
    {
        $this->array = $array;
    }

    /** Gets array from $array property
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }

    /** Returns value or null
     * @param string $field
     * @return mixed
     */
    public function get(string $field)
    {
        return $this->getArray()[$field] ?? null;
    }

    /** Returns value as integer
     * @param string $field
     * @return int
     */
    public function getInt(string $field): int
    {
        return (int)$this->get($field);
    }

    /** Returns value as float
     * @param string $field
     * @return float
     */
    public function getFloat(string $field): float
    {
        return (float)$this->get($field);
    }

    /** Returns value as string
     * @param string $field
     * @return string
     */
    public function getString(string $field): string
    {
        return (string)$this->get($field);
    }

    /** Returns value as clean string
     * @param string $field
     * @return string
     */
    public function getCleanString(string $field): string
    {
        return htmlspecialchars(trim($this->getString($field)));
    }

}
