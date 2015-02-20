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
    public function testParse($chunk, $pressure, $pressure_unit, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertEquals($pressure, $decoded['result']['pressure']);
        $this->assertEquals($pressure_unit, $decoded['result']['pressureUnit']);
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
                "pressure_unit" => 'hPA',
                "remaining" => "AAA",
            ),
            array(
                "input" => "A0202 BBB",
                "pressure" => 20.2,
                "pressure_unit" => 'inHg',
                "remaining" => "BBB",
            ),
            array(
                "input" => "Q//// CCC",
                "pressure" => null,
                "pressure_unit" => 'hPA',
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
