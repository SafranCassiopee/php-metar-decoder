<?php

use MetarDecoder\Service\DatetimeChunkDecoder;

use \DateTime;
use \DateTimeZone;
use MetarDecoder\Exception\ChunkDecoderException;

class DatetimeChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    protected $chunk_decoder;
    
    public function __construct()
    {
        $this->chunk_decoder = new DatetimeChunkDecoder();
    }
    
    public function testIsMandatory()
    {
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
            array('271035 aaa'),
            array('2102Z bbb'),
            array('123580Z LFPB'),
        );
        
        foreach($dataset as $input){
            try{
                $decoded = $this->chunk_decoder->parse($input[0]);
                $this->fail('Parsing "'.$input[0].'" should have raised an exception');
            }catch(ChunkDecoderException $cde){
                //we're cool
            }
        }
    }


}
