<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\SurfaceWindChunkDecoder;

class SurfaceWindChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new SurfaceWindChunkDecoder();
    }

    /**
     * Test parsing of valid surface wind chunks
     * @param $chunk
     * @param $direction
     * @param $speed
     * @param $speed_variations
     * @param $speed_unit
     * @param $direction_variations
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $direction, $speed, $speed_variations, $speed_unit, $direction_variations, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $wind = $decoded['result']['surfaceWind'];
        $this->assertEquals($direction, $wind->getDirection());
        $this->assertEquals($speed, $wind->getSpeed());
        $this->assertEquals($speed_variations, $wind->getSpeedVariations());
        $this->assertEquals($speed_unit, $wind->getSpeedUnit());
        $this->assertEquals($direction_variations, $wind->getDirectionVariations());
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid surface wind chunks
     * @param $chunk
     * @expectedException \MetarDecoder\Exception\ChunkDecoderException
     * @dataProvider getInvalidChunk
     */
    public function testParseInvalidChunk($chunk)
    {
        $this->decoder->parse($chunk);
    }

    public function getChunk()
    {
        return array(
            array(
                "chunk" => "VRB01MPS AAA",
                "direction" => "VRB",
                "speed" => "01",
                "speed_variations" => null,
                "speed_unit" => "MPS",
                "direction_variations" => null,
                "remaining" => "AAA"
            ),
            array(
                "chunk" => "24004MPS BBB",
                "direction" => "240",
                "speed" => "04",
                "speed_variations" => null,
                "speed_unit" => "MPS",
                "direction_variations" => null,
                "remaining" => "BBB"
            ),
            array(
                "chunk" => "140P99KT CCC",
                "direction" => "140",
                "speed" => "P99",
                "speed_variations" => null,
                "speed_unit" => "KT",
                "direction_variations" => null,
                "remaining" => "CCC"
            ),
            array(
                "chunk" => "02005MPS 350V070 DDD",
                "direction" => "020",
                "speed" => "05",
                "speed_variations" => null,
                "speed_unit" => "MPS",
                "direction_variations" => array("350","070"),
                "remaining" => "DDD"
            ),
            array(
                "chunk" => "12003G09MPS EEE",
                "direction" => "120",
                "speed" => "03",
                "speed_variations" => "09",
                "speed_unit" => "MPS",
                "direction_variations" => null,
                "remaining" => "EEE"
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "12003G09 AAA"),
            array("chunk" => "VRB01MP BBB")
        );
    }
}
