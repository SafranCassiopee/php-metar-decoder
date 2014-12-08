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
     * @param $chunk
     * @param $day
     * @param $time
     * @param $remaining
     * @dataProvider getChunk()
     */
    public function testParse($chunk, $day, $time, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $expected_time = \DateTime::createFromFormat('H:i', $time, new \DateTimeZone('UTC'));
        $this->assertEquals($day, $decoded['result']['day']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
        $this->assertEquals($expected_time, $decoded['result']['time']);
    }

    public function getChunk()
    {
        return array(
            array(
                "chunk" => "271035Z AAA",
                "day" => 27,
                "time" => "10:35",
                "remaining" => "AAA",
            ),
            array(
                "chunk" => "012342Z BBB",
                "day" => 1,
                "time" => "23:42",
                "remaining" => "BBB",
            ),
            array(
                "chunk" => "311200Z CCC",
                "day" => 31,
                "time" => "12:00",
                "remaining" => "CCC",
            ),
        );
    }

    /**
     * @expectedException \MetarDecoder\Exception\ChunkDecoderException
     * @dataProvider getInvalidChunk
     */
    public function testParseInvalidChunk($chunk)
    {
        $this->decoder->parse($chunk);
    }

    public function getInvalidChunk()
    {
        return array(
            array('271035'),
            array('2102Z'),
            array('123580Z'),
            array('12018Z'),
        );
    }
}
