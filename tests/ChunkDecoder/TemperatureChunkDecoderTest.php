<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\TemperatureChunkDecoder;

class TemperatureChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new TemperatureChunkDecoder();
    }

    /**
     * Test parsing of valid temperature chunks.
     *
     * @param string $chunk
     * @param string $air_temperature
     * @param string $dew_point_temperature
     * @param string $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $air_temperature, $dew_point_temperature, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        //var_dump($decoded);
        if ($air_temperature == null) {
            $this->assertNull($decoded['result']['airTemperature']);
        } else {
            $this->assertEquals($air_temperature, $decoded['result']['airTemperature']->getValue());
            $this->assertEquals('deg C', $decoded['result']['airTemperature']->getUnit());
        }
        if ($dew_point_temperature == null) {
            $this->assertNull($decoded['result']['dewPointTemperature']);
        } else {
            $this->assertEquals($dew_point_temperature, $decoded['result']['dewPointTemperature']->getValue());
            $this->assertEquals('deg C', $decoded['result']['dewPointTemperature']->getUnit());
        }
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                'input' => 'M01/M10 AAA',
                'air_temperature' => -1,
                'dew_point_temperature' => -10,
                'remaining' => 'AAA',
            ),
            array(
                'input' => '05/12 BBB',
                'air_temperature' => 5,
                'dew_point_temperature' => 12,
                'remaining' => 'BBB',
            ),
            array(
                'input' => '10/M01 CCC',
                'air_temperature' => 10,
                'dew_point_temperature' => -1,
                'remaining' => 'CCC',
            ),
            // partial information
            array(
                'input' => 'M15/ DDD',
                'air_temperature' => -15,
                'dew_point_temperature' => null,
                'remaining' => 'DDD',
            ),
            array(
                'input' => 'NOTHING EEE',
                'air_temperature' => null,
                'dew_point_temperature' => null,
                'remaining' => 'NOTHING EEE',
            ),
            // invalid formats
            array(
                'input' => 'M01//10 FFF',
                'air_temperature' => null,
                'dew_point_temperature' => null,
                'remaining' => 'M01//10 FFF',
            ),
            array(
                'input' => 'M1/10 GGG',
                'air_temperature' => null,
                'dew_point_temperature' => null,
                'remaining' => 'M1/10 GGG',
            ),
        );
    }
}
