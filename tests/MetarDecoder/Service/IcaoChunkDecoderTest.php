<?php

use MetarDecoder\Service\IcaoChunkDecoder;

class IcaoChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    protected $chunk_decoder;
    
    public function __construct()
    {
        $this->chunk_decoder = new IcaoChunkDecoder();
    }
    
    public function testIsMandatory(){
        $this->assertTrue($this->chunk_decoder->isMandatory());
    }
    
    public function testParse()
    {
        $dataset = array(
            'LFPG aaa' => array(array('icao' => 'LFPG'),'aaa'),
            'CNS8 bbb' => array(array('icao' => 'CNS8'),'bbb'),
            'LFPO LFPB' => array(array('icao' => 'LFPO'),'LFPB')
        );
        
        foreach($dataset as $input => $expected){
             $decoded = $this->chunk_decoder->parse($input);

             $this->assertEquals($expected[0], $decoded['result']);
             $this->assertEquals($expected[1], $decoded['remaining_metar']);
        }
    }
    
    public function testParseErrors()
    {
        $dataset = array(
            'L aaa',
            'LFP bbb',
            'LF8 LFPB'
        );
        
        foreach($dataset as $input){
             $decoded = $this->chunk_decoder->parse($input);
             $result = $decoded['result'];
             $this->assertEquals(null, $decoded['result']);
             $this->assertEquals($input, $decoded['remaining_metar']);
        }
    }

}
