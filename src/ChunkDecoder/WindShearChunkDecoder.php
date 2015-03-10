<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Entity\Value;
use MetarDecoder\Exception\ChunkDecoderException;

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
                // check runway qfu validity
                $runway = $found[3];
                $qfu_as_int = Value::toInt($runway);
                if( $qfu_as_int > 36 || $qfu_as_int < 1){
                    throw new ChunkDecoderException($remaining_metar, 'Invalid runway QFU runway visual range information', $this);
                }
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
