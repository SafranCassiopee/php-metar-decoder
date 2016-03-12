<?php

namespace MetarDecoder\Test\Entity;

use MetarDecoder\Entity\Value;

class ValueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test of value conversion.
     *
     * @param int    $value         Original value
     * @param string $unit          Unit of value
     * @param int    $new_value     Expected value
     * @param string $required_unit
     * @dataProvider getValue
     */
    public function testValueConversion($value, $unit, $new_value, $required_unit)
    {
        $value = new Value($value, $unit);
        $this->assertEquals($new_value, $value->getConvertedValue($required_unit));
    }

    /**
     * Test "Conversion rate not defined" Exception during unit conversion.
     *
     * @param int    $value         Original value
     * @param string $unit          Unit of value
     * @param string $required_unit Expected value
     * @param string $base_unit     Base unit
     * @dataProvider getValueNoRateException
     */
    public function testValueNoRateException($value, $unit, $required_unit, $base_unit)
    {
        $value = new Value($value, $unit);
        $this->setExpectedException('Exception', 'Conversion rate between "'.$base_unit.'" and "'.$required_unit.'" is not defined.');
        $value->getConvertedValue($required_unit);
    }

    /**
     * Test "Trying to convert unsupported values" Exception during unit conversion.
     *
     * @param int    $value Original value
     * @param string $unit  Unit of value
     * @dataProvider getValueUnsupportedException
     */
    public function testValueUnsupportedException($value, $unit)
    {
        $value = new Value($value, $unit);
        $this->setExpectedException('Exception', 'Trying to convert unsupported values');
        $value->getConvertedValue($unit);
    }

    public function getValue()
    {
        return array(
            array(
                'value' => 1000,
                'unit' => 'hPa',
                'new_value' => 29.53,
                'required_unit' => 'inHg',
            ),
            array(
                'value' => 2.02,
                'unit' => 'inHg',
                'new_value' => 68.405,
                'required_unit' => 'hPa',
            ),
            array(
                'value' => 800,
                'unit' => 'm',
                'new_value' => 2624.672,
                'required_unit' => 'ft',
            ),
            array(
                'value' => 5000,
                'unit' => 'ft',
                'new_value' => 1524,
                'required_unit' => 'm',
            ),
            array(
                'value' => 2500,
                'unit' => 'm',
                'new_value' => 1.553,
                'required_unit' => 'SM',
            ),
            array(
                'value' => 1,
                'unit' => 'm/s',
                'new_value' => 1.944,
                'required_unit' => 'kt',
            ),
            array(
                'value' => 99,
                'unit' => 'kt',
                'new_value' => 50.93,
                'required_unit' => 'm/s',
            ),
            array(
                'value' => 3,
                'unit' => 'km/h',
                'new_value' => 1.620,
                'required_unit' => 'kt',
            ),
            array(
                'value' => 500,
                'unit' => 'm',
                'new_value' => 500,
                'required_unit' => 'm',
            ),
            array(
                'value' => 50,
                'unit' => 'ft',
                'new_value' => 50,
                'required_unit' => 'ft',
            ),
        );
    }

    public function getValueNoRateException()
    {
        return array(
            array(
                'value' => 3,
                'unit' => 'km/h',
                'required_unit' => 'aaa',
                'base_unit' => 'm/s',
            ),
        );
    }

    public function getValueUnsupportedException()
    {
        return array(
            array(
                'value' => 3,
                'unit' => 'deg',
            ),
        );
    }
}
