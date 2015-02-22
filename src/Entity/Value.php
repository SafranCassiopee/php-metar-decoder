<?php

namespace MetarDecoder\Entity;


class Value
{
    private $value;

    private $unit;

    public function __construct($value, $unit)
    {
        $this->value = $value;

        $this->unit = $unit;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Create new temperature value (°C)
     */
    public static function newTemperatureValue($value)
    {
        return new Value(self::toInt($value), '°C');
    }

    /**
     * Convert a string value into an int, and takes into account some non-numeric char
     * P = +
     * M = -
     * / = null
     */
    public static function toInt($value)
    {
        $letter_signs = array('P','M');
        $numeric_signs = array('','-');

        $value_numeric = str_replace($letter_signs, $numeric_signs, $value);

        if (preg_match('#^[\-0-9]#', $value_numeric)) {
            return intval($value_numeric);
        } else {
            return;
        }
    }
}
