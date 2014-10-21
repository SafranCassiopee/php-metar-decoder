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
        $d = $this->decoder->parse('METAR LFPO 231027Z blabla');

        // compare results
        $this->assertEquals('METAR', $d->getType());
        $this->assertEquals('LFPO', $d->getIcao());
        $this->assertEquals('23'  , $d->getDay());
        $this->assertEquals(DateTime::createFromFormat('H:i','10:27',new DateTimeZone('UTC')) , $d->getTime());
    }
    
    public function testParseErrors()
    {   
        // TODO build a big dataset for decoding errors
        
        // launch decoder that should hit the exception
        try{
            $raw_metar = 'LFP blabla';
            $d = $this->decoder->parse($raw_metar);
            $this->fail('Decoding metar "'.$raw_metar.'" should have raised an exception');
        }catch(ChunkDecoderException $cde){
            
        }
    }

}
