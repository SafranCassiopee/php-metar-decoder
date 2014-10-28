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
        
        // launch decoder
        $d = $this->decoder->parse('METAR LFPO 231027Z AUTO blabla');

        // compare results
        $this->assertEquals('METAR', $d->getType());
        $this->assertEquals('LFPO', $d->getIcao());
        $this->assertEquals('23'  , $d->getDay());
        $this->assertEquals(DateTime::createFromFormat('H:i','10:27',new DateTimeZone('UTC')) , $d->getTime());
        $this->assertEquals('AUTO', $d->getStatus());
    }
    
    public function testParseErrors()
    {   
        // TODO build a big dataset for decoding errors
        
        // launch decoder that should hit an error
        $raw_metar = 'LFPG aaa bbb cccc';
        $d = $this->decoder->parse($raw_metar);
        
        // check the error triggered
        $this->assertFalse($d->isValid());
        $error = $d->getException();
        $this->assertEquals('AAA BBB CCCC ', $error->getChunk());
        $this->assertEquals('MetarDecoder\Service\DatetimeChunkDecoder', $error->getChunkDecoder())
;    }

}
