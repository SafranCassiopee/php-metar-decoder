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
     * Test parsing of valid cloud chunks
     * @param $chunk
     * @param $nb_layers
     * @param $layer1_amount
     * @param $layer1_base_height
     * @param $layer1_type
     * @param $visibility
     * @param $remaining
     * @dataProvider getChunk
     */
    public function testParse($chunk, $nb_layers, $layer1_amount, $layer1_base_height, $layer1_type, $visibility, $remaining)
    {
        $decoded = $this->decoder->parse($chunk);
        $clouds = $decoded['result']['clouds'];
        $cloud = $clouds[0];
        $vis = $decoded['result']['verticalVisibility'];
        $this->assertEquals($nb_layers, count($clouds));
        if($cloud != null) {
            $this->assertEquals($layer1_amount, $cloud->getAmount());
            $this->assertEquals($layer1_base_height, $cloud->getBaseHeight());
            $this->assertEquals($layer1_type, $cloud->getType());
        }
        if($vis != null){
            $this->assertEquals($visibility, $vis);
        }
        $this->assertEquals($remaining, $decoded['remaining_metar']);
    }

    /**
     * Test parsing of invalid cloud chunks
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
                "chunk" => "FEW100 VV085 AAA",
                "nb_layers" => 1,
                "layer1_amount" => "FEW",
                "layer1_base_height" => "100",
                "layer1_type" => null,
                "visibility" => "085",
                "remaining" => "AAA"
            ),
            array(
                "chunk" => "BKN200TCU OVC250 VV/// BBB",
                "nb_layers" => 2,
                "layer1_amount" => "BKN",
                "layer1_base_height" => "200",
                "layer1_type" => "TCU",
                "visibility" => "///",
                "remaining" => "BBB"
            ),
            array(
                "chunk" => "OVC////// FEW250 CCC",
                "nb_layers" => 2,
                "layer1_amount" => "OVC",
                "layer1_base_height" => "///",
                "layer1_type" => "///",
                "visibility" => null,
                "remaining" => "CCC"
            ),
            array(
                "chunk" => "NSC DDD",
                "nb_layers" => 0,
                "layer1_amount" => null,
                "layer1_base_height" => null,
                "layer1_type" => null,
                "visibility" => null,
                "remaining" => "DDD"
            ),
        );
    }

    public function getInvalidChunk()
    {
        return array(
            array("chunk" => "FEW10 EEE"),
            array("chunk" => "AAA EEE"),
            array("chunk" => "BKN100A EEE"),
        );
    }
}
