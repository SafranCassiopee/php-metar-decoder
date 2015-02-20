<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\RunwayVisualRangeChunkDecoder;

class RunwayVisualRangeChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new RunwayVisualRangeChunkDecoder();
    }

    /**
     * Test parsing of valid runway visual range chunks
     * @param $chunk
     * @param $nb_runways
     * @param $rwy1_name
     * @param $rwy1_vis
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $nb_runways, $rwy1_name, $rwy1_vis, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $runways = $decoded['result']['runwaysVisualRange'];
        $visual_range = $runways[0];
        $this->assertEquals($nb_runways, count($runways));
        $this->assertEquals($rwy1_name, $visual_range->getRunway());
        $this->assertEquals($rwy1_vis, $visual_range->getVisualRange());
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                "chunk" => "R18L/0800 AAA",
                "nb_runways" => 1,
                "rwy1_name" => "18L",
                "rwy1_vis" => 800,
                "remaining" => "AAA",
            ),
            array(
                "chunk" => "R20C/M1200 BBB",
                "nb_runways" => 1,
                "rwy1_name" => "20C",
                "rwy1_vis" => -1200,
                "remaining" => "BBB",
            ),
            array(
                "chunk" => "R12/0800 R26/0040U CCC",
                "nb_runways" => 2,
                "rwy1_name" => "12",
                "rwy1_vis" => 800,
                "remaining" => "CCC",
            ),
        );
    }

}
