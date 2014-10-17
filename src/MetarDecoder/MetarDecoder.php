<?php

namespace MetarDecoder;

use MetarDecoder\Entity\DecodedMetar;
use MetarDecoder\Service\IcaoChunkDecoder;

class MetarDecoder
{

    public function __construct()
    {
        
    }

    /**
     * Decode a full metar string
     * Under construction
     */
    public function parse($raw_metar)
    {
        $decoder = new IcaoChunkDecoder();
        $parsed = $decoder->parse($raw_metar);
        
        $decoded = new DecodedMetar();
        $decoded->setIcao($parsed['result']['icao']);
        
        return $decoded;
    }

}
