<?php

use MetarDecoder\MetarDecoder;
use MetarDecoder\Entity\DecodedMetar;
use \DateTime;
use \DateTimeZone;
use MetarDecoder\Exception\ChunkDecoderException;

class MetarDecoderTest extends PHPUnit_Framework_TestCase
{

    private $decoder;
    
    public function __construct()
    {
        $this->decoder = new MetarDecoder();
    }
    
    public function testConstruct()
    {
        $d = new MetarDecoder();
    }
    
    public function testParse()
    {
        // TODO build a big dataset for successful decoding
        
        // launch decoding for a valid metar
        $d = $this->decoder->parse('METAR LFPO 231027Z AUTO blabla');

        // compare results
        $this->assertEquals('METAR', $d->getType());
        $this->assertEquals('LFPO', $d->getIcao());
        $this->assertEquals('23'  , $d->getDay());
        $this->assertEquals(DateTime::createFromFormat('H:i','10:27',new DateTimeZone('UTC')) , $d->getTime());
        $this->assertEquals('AUTO', $d->getStatus());
    }
    
    public function testParseNil()
    {
        // empty metar, valid
        $d = $this->decoder->parse('METAR LFPO 231027Z NIL');
        $this->assertEquals('NIL', $d->getStatus());
        
    }
    
    public function testParseErrors()
    {   
        
        $error_dataset = array(
            array('LFPG aaa bbb cccc', 'DatetimeChunkDecoder', 'AAA BBB CCCC '),
            array('METAR LFPO 231027Z NIL 1234', 'ReportStatusChunkDecoder', 'NIL 1234 ')
        );
        
        foreach($error_dataset as $metar_error){
            // launch decoding
            $d = $this->decoder->parse($metar_error[0]);
            
            // check the error triggered
            $this->assertFalse($d->isValid());
            $error = $d->getException();
            $this->assertEquals('MetarDecoder\Service\\'.$metar_error[1], $error->getChunkDecoder());
            $this->assertEquals($metar_error[2], $error->getChunk());
        }

    }

}
