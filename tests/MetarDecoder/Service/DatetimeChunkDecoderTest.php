<?php

use MetarDecoder\Service\DatetimeChunkDecoder;

use \DateTime;
use \DateTimeZone;

class DatetimeChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    protected $chunk_decoder;
    
    public function __construct()
    {
        $this->chunk_decoder = new DatetimeChunkDecoder();
    }
    
    public function testIsMandatory(){
        $this->assertTrue($this->chunk_decoder->isMandatory());
    }
    
    public function testParse()
    {
        $dataset = array(
            '271035Z aaa'  => array(array('day' => '27', 'time' => DateTime::createFromFormat('H:i','10:35',new DateTimeZone('UTC'))),'aaa'),
            '012342Z bbb'  => array(array('day' => '01', 'time' => DateTime::createFromFormat('H:i','23:42',new DateTimeZone('UTC'))),'bbb'),
            '311200Z ccc' => array(array('day' => '31', 'time' => DateTime::createFromFormat('H:i','12:00',new DateTimeZone('UTC'))),'ccc'),
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
            array('271035 aaa','271035 aaa'),
            array('2102Z bbb','2102Z bbb'),
            array('123580Z LFPB','LFPB'),
        );
        
        foreach($dataset as $input){
             $decoded = $this->chunk_decoder->parse($input[0]);
             $result = $decoded['result'];
             $this->assertEquals(null, $decoded['result']);
             $this->assertEquals($input[1], $decoded['remaining_metar']);
        }
    }


}
