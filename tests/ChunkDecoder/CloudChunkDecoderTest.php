<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\CloudChunkDecoder;

class CloudChunkDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    protected function setup()
    {
        $this->decoder = new CloudChunkDecoder();
    }

    /**
     * Test parsing of valid cloud chunks.
     *
     * @param $chunk
     * @param $nb_layers
     * @param $layer1_amount
     * @param $layer1_base_height
     * @param $layer1_type
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $nb_layers, $layer1_amount, $layer1_base_height, $layer1_type, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $clouds = $decoded['result']['clouds'];
        $this->assertEquals($nb_layers, count($clouds));
        if (count($clouds) > 0) {
            $cloud = $clouds[0];
            $this->assertEquals($layer1_amount, $cloud->getAmount());
            if ($layer1_base_height != null) {
                $this->assertEquals($layer1_base_height, $cloud->getBaseHeight()->getValue());
                $this->assertEquals('ft', $cloud->getBaseHeight()->getUnit());
            } else {
                $this->assertNull($cloud->getBaseHeight());
            }
            $this->assertEquals($layer1_type, $cloud->getType());
        }
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing with invalid cloud chunks but with CAVOK earlier in the METAR.
     *
     * @param $chunk
     * @dataProvider getInvalidChunk
     */
    public function testParseCAVOKChunk($chunk)
    {
        $decoded = $this->decoder->parse($chunk, true);
        $this->assertEquals(0, count($decoded['result']['clouds']));
    }

    /**
     * Test parsing of invalid cloud chunks.
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
                'chunk' => 'VV085 AAA',
                'nb_layers' => 1,
                'layer1_amount' => 'VV',
                'layer1_base_height' => 8500,
                'layer1_type' => null,
                'remaining' => 'AAA',
            ),
            array(
                'chunk' => 'BKN200TCU OVC250 VV/// BBB',
                'nb_layers' => 3,
                'layer1_amount' => 'BKN',
                'layer1_base_height' => 20000,
                'layer1_type' => 'TCU',
                'remaining' => 'BBB',
            ),
            array(
                'chunk' => 'OVC////// FEW250 CCC',
                'nb_layers' => 2,
                'layer1_amount' => 'OVC',
                'layer1_base_height' => null,
                'layer1_type' => '///',
                'remaining' => 'CCC',
            ),
            array(
                'chunk' => 'NSC DDD',
                'nb_layers' => 0,
                'layer1_amount' => null,
                'layer1_base_height' => null,
                'layer1_type' => null,
                'remaining' => 'DDD',
            ),
            array(
                'chunk' => 'SKC EEE',
                'nb_layers' => 0,
                'layer1_amount' => null,
                'layer1_base_height' => null,
                'layer1_type' => null,
                'remaining' => 'EEE',
            ),
            array(
                'chunk' => 'NCD FFF',
                'nb_layers' => 0,
                'layer1_amount' => null,
                'layer1_base_height' => null,
                'layer1_type' => null,
                'remaining' => 'FFF',
            ),
            array(
                'chunk' => 'BKN200TCU OVC250 FEW300 FEW350 FEW400 VV/// GGG',
                'nb_layers' => 6,
                'layer1_amount' => 'BKN',
                'layer1_base_height' => 20000,
                'layer1_type' => 'TCU',
                'remaining' => 'GGG',
            ),
            array(
                'chunk' => '////// HHH',
                'nb_layers' => 1,
                'layer1_amount' => '///',
                'layer1_base_height' => null,
                'layer1_type' => null,
                'remaining' => 'HHH',
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array('chunk' => 'FEW10 EEE'),
            array('chunk' => 'AAA EEE'),
            array('chunk' => 'BKN100A EEE'),
        );
    }
}
