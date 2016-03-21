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

    private $speed_conversion_map = array(
        'base' => self::METER_PER_SECOND,
        self::METER_PER_SECOND => 1,
        self::KILOMETER_PER_HOUR => 0.277778,
        self::KNOT => 0.51444,
    );

    private $distance_conversion_map = array(
        'base' => self::METER,
        self::METER => 1,
        self::FEET => 0.3048,
        self::STATUTE_MILE => 1609.34,
    );

    private $pressure_conversion_map = array(
        'base' => self::HECTO_PASCAL,
        self::HECTO_PASCAL => 100,
        self::MERCURY_INCH => 3.386389e3,
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

    /**
     * Returns converted value of unit.
     * Adapted from https://github.com/crisu83/php-conversion.
     *
     * @param string $to Unit that you want your current unit converted to.
     *                   Accepts 'kt', 'm/s', 'km/h', 'm', 'ft', 'SM', 'hPa', 'inHg'
     *
     * @return float Returns float value rounded to 3 digits after decimal point
     *
     * @throws \Exception If there's no conversion rate or conversion not possible between values
     */
    public function getConvertedValue($to)
    {
        $rate_from = $this->getConversionRate($this->getUnit());
        $rate_to = $this->getConversionRate($to);

        return round(($this->value * $rate_from) / $rate_to, 3);
    }

    /**
     * Returns conversion rate between original METAR unit and requested unit.
     * Adapted from https://github.com/crisu83/php-conversion.
     *
     * @param string $unit An unit that we want a conversion rate for (as compared to original METAR unit)
     *
     * @return mixed Returns conversion rate between original METAR unit and requested unit
     *
     * @throws \Exception Throws exception if there's no conversion rate between original METAR unit and requested unit
     */
    private function getConversionRate($unit)
    {
        $conversion_map = $this->getConversionMap();
        if (!isset($conversion_map[$unit])) {
            throw new \Exception(sprintf(
                'Conversion rate between "%s" and "%s" is not defined.',
                $conversion_map['base'],
                $unit
            ));
        }

        return $conversion_map[$unit];
    }

    /**
     * Returns conversion map based on original METAR unit.
     *
     * @return array Returns conversion map based on unit of original METAR
     *
     * @throws \Exception Throws exception of there's no conversion map for original unit (i.e. for degrees)
     */
    private function getConversionMap()
    {
        $conversion_maps = array(
            $this->speed_conversion_map,
            $this->distance_conversion_map,
            $this->pressure_conversion_map,
        );

        foreach ($conversion_maps as $map) {
            if (array_key_exists($this->unit, $map)) {
                return $map;
            }
        }

        throw new \Exception('Trying to convert unsupported values');
    }

    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Create a new value, possibly null.
     */
    public static function newValue($value, $unit)
    {
        if ($value != null) {
            return new self($value, $unit);
        } else {
            return;
        }
    }

    /**
     * Create new integer value.
     */
    public static function newIntValue($value, $unit)
    {
        return new self(self::toInt($value), $unit);
    }

    /**
     * Convert a string value into an int, and takes into account some non-numeric char
     * P = +
     * M = -
     * / = null.
     */
    public static function toInt($value)
    {
        $letter_signs = array('P', 'M');
        $numeric_signs = array('', '-');

        $value_numeric = str_replace($letter_signs, $numeric_signs, $value);

        if (preg_match('#^[\-0-9]#', $value_numeric)) {
            return intval($value_numeric);
        } else {
            return;
        }
    }
}
