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
     * Test parsing of valid temperature chunks
     * @param string $chunk
     * @param string $air_temperature
     * @param string $dew_point_temperature
     * @param string $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $air_temperature, $dew_point_temperature, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertEquals($air_temperature, $decoded['result']['airTemperature']);
        $this->assertEquals($dew_point_temperature, $decoded['result']['dewPointTemperature']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid temperature chunks
     * @param string $chunk
     * @expectedException \MetarDecoder\Exception\ChunkDecoderException
     * @dataProvider getInvalidChunk
     */
    public function testParseInvalidIcaoChunk($chunk)
    {
        $this->decoder->parse($chunk);
    }

    public function getChunk()
    {
        return array(
            array(
                "input" => "M01/M10 AAA",
                "air_temperature" => "M01",
                "dew_point_temperature" => "M10",
                "remaining" => "AAA",
            ),
            array(
                "input" => "05/12 BBB",
                "air_temperature" => "05",
                "dew_point_temperature" => "12",
                "remaining" => "BBB",
            ),
            array(
                "input" => "10/M01 CCC",
                "air_temperature" => "10",
                "dew_point_temperature" => "M01",
                "remaining" => "CCC",
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "M01//10 AAA"),
            array("chunk" => "M1/05 BBB"),
            array("chunk" => "10/120 CCC"),
        );
    }
}
