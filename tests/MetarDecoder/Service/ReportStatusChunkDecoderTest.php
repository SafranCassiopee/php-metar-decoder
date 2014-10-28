<?php

use MetarDecoder\Service\ReportStatusChunkDecoder;

class ReportStatusChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    protected $chunk_decoder;
    
    public function __construct()
    {
        $this->chunk_decoder = new ReportStatusChunkDecoder();
    }
    
    public function testParse()
    {
        $dataset = array(
            'NIL AAA' => array(array('status' => 'NIL'),'AAA'),
            'AUTO AAA' => array(array('status' => 'AUTO'),'AAA')
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
            'BBB AAA',
            'NUL AAA',
            'AUT AAA',
            'AUTOM AAA'
        );
        
        foreach($dataset as $input){
             $decoded = $this->chunk_decoder->parse($input);
             $result = $decoded['result'];
             $this->assertEquals(null, $decoded['result']);
             $this->assertEquals($input, $decoded['remaining_metar']);
        }
    }

}
