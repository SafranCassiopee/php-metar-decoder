<?php

use MetarDecoder\MetarDecoder;
use MetarDecoder\Entity\DecodedMetar;
use \DateTime;
use \DateTimeZone;

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
        // declare the exception that should be thrown
        $this->setExpectedException(
            'Exception', 'Parsing error for MetarDecoder\Service\IcaoChunkDecoder: "LFP BLABLA"'
        );
        
        // launch decoder that should hit the exception
        $d = $this->decoder->parse('LFP blabla');

    }

}
