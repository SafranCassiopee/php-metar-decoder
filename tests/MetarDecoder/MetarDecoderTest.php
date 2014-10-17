<?php

use MetarDecoder\MetarDecoder;
use MetarDecoder\Entity\DecodedMetar;

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
        // launch decoder
        $d = $this->decoder->parse('LFPO blabla');

        // compare results
        $this->assertEquals('LFPO', $d->getIcao());
    }
    
    public function testParseErrors()
    {
        
        // declare the exception that should be thrown
        $this->setExpectedException(
            'Exception', 'Parsing error for MetarDecoder\Service\IcaoChunkDecoder: "LFP blabla"'
        );
        
        // launch decoder
        $d = $this->decoder->parse('LFP blabla');

    }

}
