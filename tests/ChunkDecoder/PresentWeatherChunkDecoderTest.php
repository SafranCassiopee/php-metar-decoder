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
     * Test parsing of valid present weather chunks.
     *
     * @param $chunk
     * @param $nb_phenoms
     * @param $intensity1
     * @param $carac1
     * @param type1
     * @param type2
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $nb_phenoms, $intensity1, $carac1, $type1, $type2, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $pw = $decoded['result']['presentWeather'];

        $this->assertEquals($nb_phenoms, count($pw));
        if ($nb_phenoms > 0) {
            $phenom1 = $pw[0];
            $this->assertEquals($intensity1, $phenom1->getIntensityProximity());
            $this->assertEquals($carac1, $phenom1->getCharacteristics());
            $this->assertEquals($type1, $phenom1->getTypes());
        }
        if ($nb_phenoms > 1) {
            $phenom2 = $pw[1];
            $this->assertEquals($type2, $phenom2->getTypes());
        }
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    public function getChunk()
    {
        return array(
            array(
                'chunk' => 'NOTHING HERE',
                'nb_phenoms' => 0,
                'intensity1' => null,
                'carac1' => null,
                'type1' => null,
                'type2' => null,
                'remaining' => 'NOTHING HERE',
            ),
            array(
                'chunk' => 'FZRA +SN BCFG AAA',
                'nb_phenoms' => 3,
                'intensity1' => null,
                'carac1' => 'FZ',
                'type1' => array('RA'),
                'type2' => array('SN'),
                'remaining' => 'AAA',
            ),
            array(
                'chunk' => '-SG BBB',
                'nb_phenoms' => 1,
                'intensity1' => '-',
                'carac1' => null,
                'type1' => array('SG'),
                'type2' => null,
                'remaining' => 'BBB',
            ),
            array(
                'chunk' => '+GSBRFU VCDRFCPY // CCC',
                'nb_phenoms' => 2,
                'intensity1' => '+',
                'carac1' => null,
                'type1' => array('GS', 'BR', 'FU'),
                'type2' => array('FC', 'PY'),
                'remaining' => 'CCC',
            ),
            array(
                'chunk' => '// DDD',
                'nb_phenoms' => 0,
                'intensity1' => null,
                'carac1' => null,
                'type1' => null,
                'type2' => null,
                'remaining' => 'DDD',
            ),
        );
    }
}
