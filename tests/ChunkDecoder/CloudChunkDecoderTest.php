<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\CloudChunkDecoder;

class CloudChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new CloudChunkDecoder();
    }

    /**
     * Test parsing of valid cloud chunks
     * @param $chunk
     * @param $nb_layers
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $nb_layers, $visibility, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $clouds = $decoded['result']['clouds'];
        $vis = $decoded['result']['verticalVisibility'];
        $this->assertEquals($nb_layers, count($clouds));
        $this->assertEquals($visibility, $vis);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid cloud chunks
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
                "chunk" => "FEW100 VV085 AAA",
                "nb_layers" => 1,
                "visibility" => "085",
                "remaining" => "AAA"
            ),
            array(
                "chunk" => "BKN200 OVC250 VV/// BBB",
                "nb_layers" => 2,
                "visibility" => "///",
                "remaining" => "BBB"
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "FEW10"),
            array("chunk" => "AAA"),
            array("chunk" => "BKN100A"),
        );
    }
}
