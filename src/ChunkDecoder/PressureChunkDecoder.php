<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for atmospheric pressure section
 */
class PressureChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{  

    public function getRegexp()
    {
        //"#^((Q|A)(////|[0-9]{4})|RMK AO[12])( )#";
        return "#^(Q|A)(////|[0-9]{4})( )#";
    }

    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        //var_dump($found);
        
        // throw error if nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar, 'Atmospheric pressure not found', $this);
        }
    
        // retrieve found params
        $result = array(
            'pressure' => $found[2],
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
