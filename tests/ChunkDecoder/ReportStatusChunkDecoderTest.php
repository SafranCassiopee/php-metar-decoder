<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\ReportStatusChunkDecoder;

class ReportStatusChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new ReportStatusChunkDecoder();
    }

    /**
     * Test parsing of valid report status chunks
     * @param $chunk
     * @param $status
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $status, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertEquals($status, $decoded['result']['status']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid report status chunks
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
                "chunk" => "NIL ",
                "status" => "NIL",
                "remaining" => "",
            ),
            array(
                "chunk" => "AUTO AAA",
                "status" => "AUTO",
                "remaining" => "AAA",
            ),
            array(
                "chunk" => "AUTO AUTO",
                "status" => "AUTO",
                "remaining" => "AUTO",
            ),
            array(
                "chunk" => "AUTO AUTO",
                "status" => "AUTO",
                "remaining" => "AUTO",
            ),
            array(
                "chunk" => "1234 BBB",
                "status" => null,
                "remaining" => "1234 BBB",
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "NIL BBB"),
            array("chunk" => "NIL NIL"),
            array("chunk" => "AUTIO 234"),
            array("chunk" => "AUT 234"),
        );
    }
}
