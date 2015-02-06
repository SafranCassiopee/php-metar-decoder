<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for air and dew point temperature section
 */
class TemperatureChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{  

    public function getRegexp()
    {       
        $temp_regex = '(M?[0-9]{2})';
        return "#^$temp_regex/$temp_regex #";
    }

    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        
        // throw error if nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar, 'Air and dew point temperature not found', $this);
        }
    
        // retrieve found params
        $result = array(
            'air_temperature' => $found[1],
            'dew_point_temperature' => $found[2]
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
