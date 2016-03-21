<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for icao section.
 */
class IcaoChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        return '#^([A-Z0-9]{4}) #';
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $result = $this->consume($remaining_metar);
        $found = $result['found'];
        $new_remaining_metar = $result['remaining'];

        // throw error if nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar,
                                            $new_remaining_metar,
                                            'Station ICAO code not found (4 char expected)',
                                            $this);
        }

        // retrieve found params
        $result = array(
            'icao' => $found[1],
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
