<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\IcaoChunkDecoder;

class IcaoChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new IcaoChunkDecoder();
    }

    /**
     * @param string $chunk
     * @param string $icao
     * @param string $remaining
     * @dataProvider getChunk
     */
    public function testParseIcaoChunk($chunk, $icao, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertEquals($icao, $decoded['result']['icao']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                "input" => "LFPG AAA",
                "icao" => "LFPG",
                "remaining" => "AAA"
            ),
            array(
                "input" => "LFPO BBB",
                "icao" => "LFPO",
                "remaining" => "BBB",
            ),
            array(
                "input" => "LFIO CCC",
                "icao" => "LFIO",
                "remaining" => "CCC"
            ),
        );
    }

    /**
     * @expectedException \MetarDecoder\Exception\ChunkDecoderException
     * @dataProvider getInvalidChunk
     */
    public function testParseInvalidIcaoChunk($chunk)
    {
        $this->decoder->parse($chunk);
    }

    public function getInvalidChunk()
    {
        return array(
            array('LFA AAA'),
            array('L AAA'),
            array('LFP BBB'),
            array('LF8 CCC'),
        );
    }
}
