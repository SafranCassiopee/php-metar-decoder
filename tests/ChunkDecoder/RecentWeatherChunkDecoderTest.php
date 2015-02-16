<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\RecentWeatherChunkDecoder;

class RecentWeatherChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new RecentWeatherChunkDecoder();
    }

    /**
     * Test parsing of valid recent weather chunks
     * @param $chunk
     * @param $recent_weather
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $recent_weather, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $this->assertEquals($recent_weather, $decoded['result']['recentWeather']);
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                "chunk" => "REBLSN AAA",
                "recent_weather" => "BLSN",
                "remaining" => "AAA",
            ),
            array(
                "chunk" => "REPL BBB",
                "recent_weather" => "PL",
                "remaining" => "BBB",
            ),
            array(
                "chunk" => "RETSRA CCC",
                "recent_weather" => "TSRA",
                "remaining" => "CCC",
            ),
        );
    }
}
