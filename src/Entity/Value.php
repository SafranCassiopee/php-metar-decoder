<?php

namespace MetarDecoder\Entity;

class Value
{
    private $value;

    private $unit;

    const DEGREE_CELSIUS = '°C';
    CONST DEGREE = '°';
    CONST KNOT = 'kt';
    const METERS_PER_SECOND = 'm/s';
    
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
     * Create new integer value
     */
    public static function newIntValue($value, $unit)
    {
        return new Value(self::toInt($value), $unit);
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
