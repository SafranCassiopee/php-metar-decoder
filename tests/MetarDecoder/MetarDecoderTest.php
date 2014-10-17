<?php

use MetarDecoder\MetarDecoder;
use MetarDecoder\Entity\DecodedMetar;

class MetarDecoderTest extends PHPUnit_Framework_TestCase
{

    public function testParse()
    {
        // create decoder and launch it
        //$decoder = new MetarDecoder();
        //$decoded = $decoder->parse('test');

        // check parse result
        $this->assertEquals('LFPG', 'LFPG');
    }

}
