<?php

namespace MetarDecoder\ChunkDecoder;

/**
 * Chunk decoder for atmospheric pressure section
 */
class WindShearChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        return "#^WS (R([0-9]{2}[LCR]?)|(ALL) RWY)( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            $result = null;
        } else {
            // detect if we have windshear on all runway or only one
            empty($found[2]) ? $runway = 'all' : $runway = $found[2];
            $result = array(
                'windshear_runway' => $runway,
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
