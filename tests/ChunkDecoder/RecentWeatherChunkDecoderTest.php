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
     * @param $weather_carac
     * @param $weather_type
     * @dataProvider getChunk
     */
    public function testParse($chunk, $weather_carac, $weather_type, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $recent = $decoded['result']['recentWeather'];
        $this->assertEquals($weather_carac, $recent->getCharacteristics());
        $this->assertEquals($weather_type, current($recent->getTypes()));
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                "chunk" => "REBLSN AAA",
                "weather_carac" => "BL",
                "weather_type" => "SN",
                "remaining" => "AAA",
            ),
            array(
                "chunk" => "REPL BBB",
                "weather_carac" => "",
                "weather_type" => "PL",
                "remaining" => "BBB",
            ),
            array(
                "chunk" => "RETSRA CCC",
                "weather_carac" => "TS",
                "weather_type" => "RA",
                "remaining" => "CCC",
            ),
        );
    }
}
