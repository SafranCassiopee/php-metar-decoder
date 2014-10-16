<?php

namespace MetarDecoder;

use MetarDecoder\Entity\DecodedMetar;
use MetarDecoder\Service\MetarChunkDecoder;

class MetarDecoder
{

    public function __construct()
    {
        
    }

    public function parse($raw_metar)
    {

        $decoder = new MetarChunkDecoder();
        $result = $decoder->parse($raw_metar);
        
        $decoded = new DecodedMetar();
        $decoded->setIcao($result['icao'])
                ->setDatetime($result['datetime']);
        
        return $decoded;
    }

}
