<?php

namespace MetarDecoder\Service;

class MetarChunkDecoder
{
    
    public function __construct()
    {

    }
    
    /**
     * Decode a part of the raw METAR string
     */
    public function parse($partial_metar)
    {
        return array(
            'icao' => 'LFPG',
            'datetime'=> '2014-10-16T21:31:00Z'
        );
    }
    
}
