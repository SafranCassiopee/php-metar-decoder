<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\WindShearChunkDecoder;

class WindShearChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new WindShearChunkDecoder();
    }

    /**
     * Test parsing of valid windshear chunks
     * @param string $chunk
     * @param string $runway
     * @param string $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $runway, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertEquals($runway, $decoded['result']['windshearRunway']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid wind shear chunks
     * @param string $chunk
     * @dataProvider getInvalidChunk
     */
    public function testParseInvalidIcaoChunk($chunk)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertNull($decoded['result']);
        $this->assertEquals($chunk, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                "input" => "WS R03 AAA",
                "runway" => "03",
                "remaining" => "AAA",
            ),
            array(
                "input" => "WS R18C BBB",
                "runway" => "18C",
                "remaining" => "BBB",
            ),
            array(
                "input" => "WS ALL RWY CCC",
                "runway" => "all",
                "remaining" => "CCC",
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "W RWY AAA"),
            array("chunk" => "WS ALL BBB"),
            array("chunk" => "WS R12P CCC"),
        );
    }
}
