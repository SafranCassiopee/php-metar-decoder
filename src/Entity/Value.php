<?php

namespace MetarDecoder\Entity;

class Value
{
    private $value;

    private $unit;

    const DEGREE_CELSIUS = 'deg C';
    const DEGREE = 'deg';
    const KNOT = 'kt';
    const METER_PER_SECOND = 'm/s';
    const KILOMETER_PER_HOUR = 'km/h';
    const METER = 'm';
    const FEET = 'ft';
    const STATUTE_MILE = 'SM';
    const HECTO_PASCAL = 'hPa';
    const MERCURY_INCH = 'inHg';
    const UNKNOWN_UNIT = 'N/A';

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
     * Create a new value, possibly null
     */
    public static function newValue($value, $unit)
    {
        if ($value != null) {
            return new Value($value, $unit);
        } else {
            return;
        }
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
