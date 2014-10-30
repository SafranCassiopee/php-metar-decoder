<?php

use MetarDecoder\ChunkDecoder\ReportTypeChunkDecoder;

class ReportTypeChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    protected $chunk_decoder;
    
    public function __construct()
    {
        $this->chunk_decoder = new ReportTypeChunkDecoder();
    }
    
    public function testParse()
    {
        $dataset = array(
            'METAR LFPG' => array(array('type' => 'METAR'),'LFPG'),
            'SPECI LFPB' => array(array('type' => 'SPECI'),'LFPB'),
            'METAR COR LFPO' => array(array('type' => 'METAR COR'),'LFPO')
        );
        
        // iterate on all the dataset
        foreach($dataset as $input => $expected){
             // call to chunk decoder
             $decoded = $this->chunk_decoder->parse($input);
             $this->assertEquals($expected[0], $decoded['result']);
             $this->assertEquals($expected[1], $decoded['remaining_metar']);
        }
    }
    
    public function testParseErrors()
    {
        $dataset = array(
            'META LFPG',
            'SPECIA LFPG',
            'META COR LFPB'
        );
        
        foreach($dataset as $input){
             $decoded = $this->chunk_decoder->parse($input);
             $result = $decoded['result'];
             $this->assertEquals(null, $decoded['result']);
             $this->assertEquals($input, $decoded['remaining_metar']);
        }
    }

}
