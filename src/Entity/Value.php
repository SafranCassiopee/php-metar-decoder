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

    private $speedConst = array(self::KNOT, self::METER_PER_SECOND, self::KILOMETER_PER_HOUR);
    private $distanceConst = array(self::METER, self::FEET, self::STATUTE_MILE);
    private $pressureConst = array(self::HECTO_PASCAL, self::MERCURY_INCH);

    private $speedConversionMap = array(
        'base' => self::METER_PER_SECOND,
        self::METER_PER_SECOND => 1,
        self::KILOMETER_PER_HOUR => 0.277778,
        self::KNOT => 0.51444
    );

    private $distanceConversionMap = array(
        'base' => self::METER,
        self::METER => 1,
        self::FEET => 0.3048,
        self::STATUTE_MILE => 1609.34
    );

    private $pressureConversionMap = array(
        'base' => self::HECTO_PASCAL,
        self::HECTO_PASCAL => 1,
        self::MERCURY_INCH => 3.386389e3
    );

    public function __construct($value, $unit)
    {
        $this->value = $value;

        $this->unit = $unit;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getConvertedValue($to)
    {
        return round(($this->value * $this->getConversionRate($this->getUnit())) / $this->getConversionRate($to), 3);
    }

    private function getConversionRate($unit)
    {
        $conversionMap = $this->getConversionMap();
        if (!isset($conversionMap[$unit])) {
            throw new \Exception(sprintf(
                'Conversion rate between "%s" and "%s" is not defined.',
                $conversionMap['base'],
                $unit
            ));
        }
        return $conversionMap[$unit];
    }

    private function getConversionMap()
    {
        if(in_array($this->unit, $this->speedConst))
        {
            return $this->speedConversionMap;
        }
        elseif(in_array($this->unit, $this->distanceConst))
        {
            return $this->distanceConversionMap;
        }
        elseif(in_array($this->unit, $this->pressureConst))
        {
            return $this->pressureConversionMap;
        }
        else
        {
            throw new \Exception("Trying to convert unsupported values");
        }
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
