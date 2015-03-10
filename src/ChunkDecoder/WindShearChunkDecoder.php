<?php

namespace MetarDecoder\ChunkDecoder;

/**
 * Chunk decoder for atmospheric pressure section
 */
class WindShearChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        return "#^WS (R(WY)?([0-9]{2}[LCR]?)|(ALL) RWY)( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            $result = null;
        } else {
            // detect if we have windshear on all runway or only one
            if(empty($found[3])){
                $runway = 'all';
            }else{
                $runway = $found[3];
            }
            $result = array(
                'windshearRunway' => $runway,
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
