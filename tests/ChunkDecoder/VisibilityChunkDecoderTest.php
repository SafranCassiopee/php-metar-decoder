<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\VisibilityChunkDecoder;

class VisibilityChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new VisibilityChunkDecoder();
    }

    /**
     * Test parsing of valid surface wind chunks.
     *
     * @param $chunk
     * @param $cavok
     * @param $visibility
     * @param $visibility_unit
     * @param $minimum
     * @param $minimum_direction
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $cavok, $visibility, $visibility_unit, $minimum, $minimum_direction, $has_ndv, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        if ($cavok) {
            $this->assertTrue($decoded['result']['cavok']);
        } elseif ($visibility == null) {
            $this->assertNull($decoded['result']['visibility']);
            $this->assertFalse($decoded['result']['cavok']);
        } else {
            $vis = $decoded['result']['visibility'];
            $this->assertEquals($visibility, $vis->getVisibility()->getValue());
            $this->assertEquals($visibility_unit, $vis->getVisibility()->getUnit());
            $this->assertEquals($has_ndv, $vis->hasNDV());
            if ($minimum != null) {
                $this->assertEquals($minimum, $vis->getMinimumVisibility()->getValue());
                $this->assertEquals($minimum_direction, $vis->getMinimumVisibilityDirection());
            }
        }
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

    public function getChunk()
    {
        return array(
            array(
                'chunk' => '0200 AAA',
                'cavok' => false,
                'visibility' => 200,
                'visibility_unit' => 'm',
                'minimum' => null,
                'minimum_direction' => null,
                'ndv' => false,
                'remaining' => 'AAA',
            ),
            array(
                'chunk' => 'CAVOK BBB',
                'cavok' => true,
                'visibility' => null,
                'visibility_unit' => 'm',
                'minimum' => null,
                'minimum_direction' => null,
                'ndv' => false,
                'remaining' => 'BBB',
            ),
            array(
                'chunk' => '8000 1200N CCC',
                'cavok' => false,
                'visibility' => 8000,
                'visibility_unit' => 'm',
                'minimum' => 1200,
                'minimum_direction' => 'N',
                'ndv' => false,
                'remaining' => 'CCC',
            ),
            array(
                'chunk' => '2500 2200 DDD',
                'cavok' => false,
                'visibility' => 2500,
                'visibility_unit' => 'm',
                'minimum' => 2200,
                'minimum_direction' => null,
                'ndv' => false,
                'remaining' => 'DDD',
            ),
            array(
                'chunk' => '1 1/4SM EEE',
                'cavok' => false,
                'visibility' => 1.25,
                'visibility_unit' => 'SM',
                'minimum' => null,
                'minimum_direction' => null,
                'ndv' => false,
                'remaining' => 'EEE',
            ),
            array(
                'chunk' => '10SM FFF',
                'cavok' => false,
                'visibility' => 10,
                'visibility_unit' => 'SM',
                'minimum' => null,
                'minimum_direction' => null,
                'ndv' => false,
                'remaining' => 'FFF',
            ),
            array(
                'chunk' => '3/4SM GGG',
                'cavok' => false,
                'visibility' => 0.75,
                'visibility_unit' => 'SM',
                'minimum' => null,
                'minimum_direction' => null,
                'ndv' => false,
                'remaining' => 'GGG',
            ),
            array(
                'chunk' => '//// HHH',
                'cavok' => false,
                'visibility' => null,
                'visibility_unit' => null,
                'minimum' => null,
                'minimum_direction' => null,
                'ndv' => false,
                'remaining' => 'HHH',
            ),
            array(
                'chunk' => '9000NDV JJJ',
                'cavok' => false,
                'visibility' => 9000,
                'visibility_unit' => 'm',
                'minimum' => null,
                'minimum_direction' => null,
                'ndv' => true,
                'remaining' => 'JJJ',
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array('chunk' => 'CAVO EEE'),
            array('chunk' => 'CAVOKO EEE'),
            array('chunk' => '123 EEE'),
            array('chunk' => '12335 EEE'),
            array('chunk' => '1233NVV EEE'),
        );
    }
}
