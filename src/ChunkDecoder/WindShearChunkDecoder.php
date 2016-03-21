<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Entity\Value;
use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for atmospheric pressure section.
 */
class WindShearChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $runway = 'WS R(WY)?([0-9]{2}[LCR]?)';

        return "#^(WS ALL RWY|($runway)( $runway)?( $runway)?)( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $result = $this->consume($remaining_metar);
        $found = $result['found'];
        $new_remaining_metar = $result['remaining'];

        // handle the case where nothing has been found
        if ($found == null) {
            $result = null;
        } else {
            // detect if we have windshear on all runway or only one
            if ($found[1] == 'WS ALL RWY') {
                $all = true;
                $runways = null;
            } else {
                // one or more runways, build array
                $all = false;
                $runways = array();
                for ($k = 2; $k < 9; $k += 3) {
                    if ($found[$k] != null) {
                        $runway = $found[$k + 2];
                        $qfu_as_int = Value::toInt($runway);
                        // check runway qfu validity
                        if ($qfu_as_int > 36 || $qfu_as_int < 1) {
                            throw new ChunkDecoderException($remaining_metar,
                                                            $new_remaining_metar,
                                                            'Invalid runway QFU runway visual range information',
                                                            $this);
                        }
                        $runways[] = $runway;
                    }
                }
            }
            $result = array(
                'windshearAllRunways' => $all,
                'windshearRunways' => $runways,
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
