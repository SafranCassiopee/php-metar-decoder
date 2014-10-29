<?php

use MetarDecoder\Service\SurfaceWindChunkDecoder;
use MetarDecoder\Exception\ChunkDecoderException;

class SurfaceWindChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    protected $chunk_decoder;
    
    public function __construct()
    {
        $this->chunk_decoder = new SurfaceWindChunkDecoder();
    }
    
    public function testParse()
    {
        $dataset = array(
            array( 'VRB01MPS AAA', 'AAA',
                    array(
                        'direction' => 'VRB',
                        'speed' => '01',
                        'speedUnit' => 'MPS'
                    )),
            array( '24004MPS AAA', 'AAA',
                    array(
                        'direction' => '240',
                        'speed' => '04',
                        'speedUnit' => 'MPS'
                    )),
            array( '140P99KT AAA', 'AAA',
                    array(
                        'direction' => '140',
                        'speed' => 'P99',
                        'speedUnit' => 'KT'
                    )),
            array( '02005MPS 350V070 AAA', 'AAA',
                    array(
                        'direction' => '020',
                        'speed' => '05',
                        'speedUnit' => 'MPS',
                        'directionVariations' => array('350','070')
                    )),
            array( '12003G09MPS AAA', 'AAA',
                    array(
                        'direction' => '120',
                        'speed' => '03',
                        'speedUnit' => 'MPS',
                        'speedVariations' => '09',
                    )),
        );
        
        // iterate on all the dataset
        foreach($dataset as $data){
             // call to chunk decoder
             $decoded = $this->chunk_decoder->parse($data[0]);
             $wind = $decoded['result']['surfaceWind'];
             // compare different field from wind object
             foreach($data[2] as $key => $value){
                 $getter_name = 'get'.ucfirst($key);
                 $this->assertEquals($value, $wind->$getter_name());
             }
             $this->assertEquals($data[1], $decoded['remaining_metar']);
        }
    }
    
    public function testParseErrors()
    {
        $dataset = array(
            '12003G09 AAA',
            'VRB01MP AAA'
        );
        
        foreach($dataset as $input){
            try{
                $decoded = $this->chunk_decoder->parse($input);
                $this->fail('Parsing "'.$input.'" should have raised an exception');
            }catch(ChunkDecoderException $cde){
                //we're cool
            }
        }
    }

}
