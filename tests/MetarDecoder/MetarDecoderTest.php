<?php

use MetarDecoder\MetarDecoder;
use MetarDecoder\Entity\DecodedMetar;

class MetarDecoderTest extends PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        // create decoder and launch it
        $decoder = new MetarDecoder();
        $decoded = $decoder->parse('test');

        // check parse result
        $this->assertEquals('LFPG', $decoded->getIcao());
        $this->assertEquals('2014-10-16T21:31:00Z', $decoded->getDatetime());
    }

}
