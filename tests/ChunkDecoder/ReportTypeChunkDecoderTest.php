<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\ReportTypeChunkDecoder;

class ReportTypeChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new ReportTypeChunkDecoder();
    }

    /**
     * Test parsing of valid report type chunks.
     *
     * @param $chunk
     * @param $type
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $type, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertEquals($type, $decoded['result']['type']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                'chunk' => 'METAR LFPG',
                'type' => 'METAR',
                'remaining' => 'LFPG',
            ),
            array(
                'chunk' => 'SPECI LFPB',
                'type' => 'SPECI',
                'remaining' => 'LFPB',
            ),
            array(
                'chunk' => 'METAR COR LFPO',
                'type' => 'METAR COR',
                'remaining' => 'LFPO',
            ),
            array(
                'chunk' => 'SPECI COR PPP',
                'type' => 'SPECI COR',
                'remaining' => 'PPP',
            ),
            array(
                'chunk' => 'META LFPG',
                'type' => null,
                'remaining' => 'META LFPG',
            ),
            array(
                'chunk' => 'SPECIA LFPG',
                'type' => null,
                'remaining' => 'SPECIA LFPG',
            ),
            array(
                'chunk' => 'META COR LFPB',
                'type' => null,
                'remaining' => 'META COR LFPB',
            ),
            array(
                'chunk' => '123 LFPO',
                'type' => null,
                'remaining' => '123 LFPO',
            ),
        );
    }
}
