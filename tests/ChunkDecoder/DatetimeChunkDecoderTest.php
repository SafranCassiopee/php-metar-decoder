<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\DatetimeChunkDecoder;

class DatetimeChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new DatetimeChunkDecoder();
    }

    /**
     * Test parsing of valid datetime chunks.
     *
     * @param $chunk
     * @param $day
     * @param $time
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $day, $time, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $expected_time = $time.' UTC';
        $this->assertEquals($day, $decoded['result']['day']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
        $this->assertEquals($expected_time, $decoded['result']['time']);
    }

    /**
     * Test parsing of invalid datetime chunks.
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
                'chunk' => '271035Z AAA',
                'day' => 27,
                'time' => '10:35',
                'remaining' => 'AAA',
            ),
            array(
                'chunk' => '012342Z BBB',
                'day' => 1,
                'time' => '23:42',
                'remaining' => 'BBB',
            ),
            array(
                'chunk' => '311200Z CCC',
                'day' => 31,
                'time' => '12:00',
                'remaining' => 'CCC',
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array('chunk' => '271035 AAA'),
            array('chunk' => '2102Z AAA'),
            array('chunk' => '123580Z AAA'),
            array('chunk' => '122380Z AAA'),
            array('chunk' => '351212Z AAA'),
            array('chunk' => '35018Z AAA'),
        );
    }
}
