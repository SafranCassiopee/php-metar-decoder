<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\SurfaceWindChunkDecoder;
use MetarDecoder\Exception\ChunkDecoderException;

class SurfaceWindChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new SurfaceWindChunkDecoder();
    }

    /**
     * Test parsing of valid surface wind chunks.
     *
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
        if (!$variable_direction) {
            $this->assertEquals($direction, $wind->getMeanDirection()->getValue());
            $this->assertEquals('deg', $wind->getMeanDirection()->getUnit());
        }
        $this->assertEquals($variable_direction, $wind->withVariableDirection());
        // the next 4 lines are to be compatible with PHP 5.3
        $dir_var_min = $wind->getDirectionVariations();
        $dir_var_min = $dir_var_min[0];
        $dir_var_max = $wind->getDirectionVariations();
        $dir_var_max = $dir_var_max[1];
        if ($direction_variations != null) {
            $this->assertEquals($direction_variations[0], $dir_var_min->getValue());
            $this->assertEquals($direction_variations[1], $dir_var_max->getValue());
            $this->assertEquals('deg', $dir_var_min->getUnit());
        }
        $this->assertEquals($speed, $wind->getMeanSpeed()->getValue());
        if ($speed_variations != null) {
            $this->assertEquals($speed_variations, $wind->getSpeedVariations()->getValue());
        }
        $this->assertEquals($speed_unit, $wind->getMeanSpeed()->getUnit());
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid surface wind chunks.
     *
     * @param $chunk
     * @expectedException \MetarDecoder\Exception\ChunkDecoderException
     * @dataProvider getInvalidChunk
     */
    public function testParseInvalidChunk($chunk)
    {
        $this->decoder->parse($chunk);
    }

    /**
     * Test parsing of chunk with no information.
     */
    public function testEmptyInformationChunk()
    {
        try {
            $this->decoder->parse('/////KT PPP');
            $this->fail('An exception should have been thrown here');
        } catch (ChunkDecoderException $cde) {
            $this->assertEquals('PPP', $cde->getRemainingMetar());
        }
    }

    public function getChunk()
    {
        return array(
            array(
                'chunk' => 'VRB01MPS AAA',
                'direction' => null,
                'variable_direction' => true,
                'speed' => 1,
                'speed_variations' => null,
                'speed_unit' => 'm/s',
                'direction_variations' => null,
                'remaining' => 'AAA',
            ),
            array(
                'chunk' => '24004MPS BBB',
                'direction' => 240,
                'variable_direction' => false,
                'speed' => 4,
                'speed_variations' => null,
                'speed_unit' => 'm/s',
                'direction_variations' => null,
                'remaining' => 'BBB',
            ),
            array(
                'chunk' => '140P99KT CCC',
                'direction' => 140,
                'variable_direction' => false,
                'speed' => 99,
                'speed_variations' => null,
                'speed_unit' => 'kt',
                'direction_variations' => null,
                'remaining' => 'CCC',
            ),
            array(
                'chunk' => '02005MPS 350V070 DDD',
                'direction' => 20,
                'variable_direction' => false,
                'speed' => 5,
                'speed_variations' => null,
                'speed_unit' => 'm/s',
                'direction_variations' => array(350, 70),
                'remaining' => 'DDD',
            ),
            array(
                'chunk' => '12003KPH FFF',
                'direction' => 120,
                'variable_direction' => false,
                'speed' => 3,
                'speed_variations' => null,
                'speed_unit' => 'km/h',
                'direction_variations' => null,
                'remaining' => 'FFF',
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array('chunk' => '12003G09 AAA'),
            array('chunk' => 'VRB01MP BBB'),
            array('chunk' => '38003G12MPS CCC'),
            array('chunk' => '12003KPA DDD'),
            array('chunk' => '02005MPS 450V070 EEE'),
            array('chunk' => '02005MPS 110V600 FFF'),
        );
    }
}
