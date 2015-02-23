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
     * @param $variable_direction
     * @param $speed
     * @param $speed_variations
     * @param $speed_unit
     * @param $direction_variations
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $direction, $variable_direction, $speed, $speed_variations, $speed_unit, $direction_variations, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $wind = $decoded['result']['surfaceWind'];
        if(!$variable_direction){
            $this->assertEquals($direction, $wind->getDirection()->getValue());
            $this->assertEquals('°', $wind->getDirection()->getUnit());
        }
        $this->assertEquals($variable_direction, $wind->withVariableDirection());
        $this->assertEquals($direction_variations[0], $wind->getDirectionVariations()[0]->getValue());
        $this->assertEquals($direction_variations[1], $wind->getDirectionVariations()[1]->getValue());
        $this->assertEquals('°', $wind->getDirectionVariations()[0]->getUnit());
        $this->assertEquals($speed, $wind->getSpeed()->getValue());
        $this->assertEquals($speed_variations, $wind->getSpeedVariations()->getValue());
        $this->assertEquals($speed_unit, $wind->getSpeed()->getUnit());

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
                "direction" => null,
                "variable_direction" => true,
                "speed" => 1,
                "speed_variations" => null,
                "speed_unit" => "m/s",
                "direction_variations" => null,
                "remaining" => "AAA",
            ),
            array(
                "chunk" => "24004MPS BBB",
                "direction" => 240,
                "variable_direction" => false,
                "speed" => 4,
                "speed_variations" => null,
                "speed_unit" => "m/s",
                "direction_variations" => null,
                "remaining" => "BBB",
            ),
            array(
                "chunk" => "140P99KT CCC",
                "direction" => 140,
                "variable_direction" => false,
                "speed" => 99,
                "speed_variations" => null,
                "speed_unit" => "kt",
                "direction_variations" => null,
                "remaining" => "CCC",
            ),
            array(
                "chunk" => "02005MPS 350V070 DDD",
                "direction" => 20,
                "variable_direction" => false,
                "speed" => 5,
                "speed_variations" => null,
                "speed_unit" => "m/s",
                "direction_variations" => array(350,70),
                "remaining" => "DDD",
            ),
            array(
                "chunk" => "12003G09MPS EEE",
                "direction" => 120,
                "variable_direction" => false,
                "speed" => 3,
                "speed_variations" => 9,
                "speed_unit" => "m/s",
                "direction_variations" => null,
                "remaining" => "EEE",
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "12003G09 AAA"),
            array("chunk" => "VRB01MP BBB"),
        );
    }
}
