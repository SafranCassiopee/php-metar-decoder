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
     * Test parsing of valid surface wind chunks
     * @param $chunk
     * @param $cavok
     * @param $visibility
     * @param $minimum
     * @param $minimum_direction
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $cavok, $visibility, $minimum, $minimum_direction, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        if ($cavok) {
            $this->assertTrue($decoded['result']['cavok']);
        } else {
            $vis = $decoded['result']['visibility'];
            $this->assertEquals($visibility, $vis->getVisibility()->getValue());
            $this->assertEquals($minimum, $vis->getMinimumVisibility()->getValue());
            $this->assertEquals('m', $vis->getVisibility()->getUnit());
            $this->assertEquals($minimum_direction, $vis->getMinimumVisibilityDirection());
        }
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
                "chunk" => "0200 AAA",
                "cavok" => false,
                "visibility" => 200,
                "minimum" => null,
                "minimum_direction" => null,
                "remaining" => "AAA",
            ),
            array(
                "chunk" => "CAVOK BBB",
                "cavok" => true,
                "visibility" => null,
                "minimum" => null,
                "minimum_direction" => null,
                "remaining" => "BBB",
            ),
            array(
                "chunk" => "8000 1200N CCC",
                "cavok" => false,
                "visibility" => 8000,
                "minimum" => 1200,
                "minimum_direction" => "N",
                "remaining" => "CCC",
            ),
            array(
                "chunk" => "2500 2200 DDD",
                "cavok" => false,
                "visibility" => 2500,
                "minimum" => 2200,
                "minimum_direction" => null,
                "remaining" => "DDD",
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "CAVO EEE"),
            array("chunk" => "CAVOKO EEE"),
            array("chunk" => "123 EEE"),
            array("chunk" => "12335 EEE"),
        );
    }
}
