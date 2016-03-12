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
     * Test parsing of valid runway visual range chunks.
     *
     * @param $chunk
     * @param $nb_runways
     * @param $rwy1_name
     * @param $rwy1_vis
     * @param $rwy1_unit
     * @param $rwy1_interval
     * @param $rwy1_variable
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $nb_runways, $rwy1_name, $rwy1_vis, $rwy1_unit, $rwy1_interval, $rwy1_variable, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $runways = $decoded['result']['runwaysVisualRange'];
        $visual_range = $runways[0];
        $this->assertEquals($nb_runways, count($runways));
        $this->assertEquals($rwy1_name, $visual_range->getRunway());
        $this->assertEquals($rwy1_variable, $visual_range->isVariable());
        if ($rwy1_variable) {
            $interval = $visual_range->getVisualRangeInterval();
            $min = $interval[0];
            $max = $interval[1];
            $this->assertEquals($rwy1_interval, array($min->getValue(), $max->getValue()));
            $this->assertEquals($rwy1_unit, $min->getUnit());
        } else {
            $this->assertEquals($rwy1_vis, $visual_range->getVisualRange()->getValue());
            $this->assertEquals($rwy1_unit, $visual_range->getVisualRange()->getUnit());
        }
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid runway visual range chunks.
     *
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
                'chunk' => 'R18L/0800 AAA',
                'nb_runways' => 1,
                'rwy1_name' => '18L',
                'rwy1_vis' => 800,
                'rwy1_unit' => 'm',
                'rwy1_interval' => null,
                'rw1_variable' => false,
                'remaining' => 'AAA',
            ),
            array(
                'chunk' => 'R20C/M1200 BBB',
                'nb_runways' => 1,
                'rwy1_name' => '20C',
                'rwy1_vis' => 1200,
                'rwy1_unit' => 'm',
                'rwy1_interval' => null,
                'rw1_variable' => false,
                'remaining' => 'BBB',
            ),
            array(
                'chunk' => 'R12/M0800VP1200 R26/0040U CCC',
                'nb_runways' => 2,
                'rwy1_name' => '12',
                'rwy1_vis' => null,
                'rwy1_unit' => 'm',
                'rwy1_interval' => array(800, 1200),
                'rw1_variable' => true,
                'remaining' => 'CCC',
            ),
            array(
                'chunk' => 'R30/5000FT R26/2500V3000FTU DDD',
                'nb_runways' => 2,
                'rwy1_name' => '30',
                'rwy1_vis' => 5000,
                'rwy1_unit' => 'ft',
                'rwy1_interval' => null,
                'rw1_variable' => false,
                'remaining' => 'DDD',
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array('chunk' => 'R42L/0500 AAA'),
            array('chunk' => 'R00C/0050 BBB'),
        );
    }
}
