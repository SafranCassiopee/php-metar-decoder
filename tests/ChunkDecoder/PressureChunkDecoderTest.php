<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\PressureChunkDecoder;

class PressureChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new PressureChunkDecoder();
    }

    /**
     * Test parsing of valid pressure chunks
     * @param string $chunk
     * @param string $pressure
     * @param string $pressure_unit
     * @param string $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $pressure, $pressure_hPa, $pressure_inHg, $pressure_unit, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        if ($pressure != null) {
            $this->assertEquals($pressure, $decoded['result']['pressure']->getValue());
            $this->assertEquals($pressure_unit, $decoded['result']['pressure']->getUnit());
            $this->assertEquals($pressure_hPa, $decoded['result']['pressure']->getConvertedValue('hPa'));
            $this->assertEquals($pressure_inHg, $decoded['result']['pressure']->getConvertedValue('inHg'));
        } else {
            $this->assertNull($decoded['result']['pressure']);
        }
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid pressure chunks
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
                "input" => "Q1000 AAA",
                "pressure" => 1000,
                "pressure_hPa" => 1000,
                "pressure_inHg" => 29.53,
                "pressure_unit" => 'hPa',
                "remaining" => "AAA",
            ),
            array(
                "input" => "A0202 BBB",
                "pressure" => 2.02,
                "pressure_hPa" => 68.405,
                "pressure_inHg" => 2.02,
                "pressure_unit" => 'inHg',
                "remaining" => "BBB",
            ),
            array(
                "input" => "Q//// CCC",
                "pressure" => null,
                "pressure_hPa" => null,
                "pressure_inHg" => null,
                "pressure_unit" => null,
                "remaining" => "CCC",
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "Q123 AAA"),
            array("chunk" => "R1234 BBB"),
            array("chunk" => "Q12345 CCC"),
        );
    }
}
