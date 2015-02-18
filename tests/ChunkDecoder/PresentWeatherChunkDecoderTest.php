<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\PresentWeatherChunkDecoder;

class PresentWeatherChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new PresentWeatherChunkDecoder();
    }

    /**
     * Test parsing of valid present weather chunks
     * @param $chunk
     * @param $precipitations
     * @param $obstacles
     * @param $vicinities
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $precipitations, $obstacles, $vicinities, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $present_weather = $decoded['result']['presentWeather'];
        $this->assertEquals($precipitations, implode(' ', $present_weather->getPrecipitations()));
        $this->assertEquals($obstacles, implode(' ', $present_weather->getObstacles()));
        $this->assertEquals($vicinities, implode(' ', $present_weather->getVicinities()));
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                "chunk" => "FZRA +SN BCFG VCFG AAA",
                "precipitations" => "FZRA +SN",
                "obstacles" => "BCFG",
                "vicinities" => "FG",
                "remaining" => "AAA",
            ),
            array(
                "chunk" => "TSUP -SG BR DU VCFC VCBLSA BBB",
                "precipitations" => "TSUP -SG",
                "obstacles" => "BR DU",
                "vicinities" => "FC BLSA",
                "remaining" => "BBB",
            ),
        );
    }
}
