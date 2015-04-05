<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for report status section (NIL or AUTO)
 */
class ReportStatusChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        return '#^([A-Z]+) #';
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            $result = null;
        } else {
            $status = $found[1];
            if (strlen($status) != 3 && $status != "AUTO") {
                throw new ChunkDecoderException($remaining_metar, 'Invalid report status, expecting AUTO, NIL, or any other 3 letter word', $this);
            }
            // retrieve found params
            $result = array(
                'status' => $status,
            );
        }
        $next_remaining_metar = $this->getRemainingMetar($remaining_metar);

        // in the case where status is NIL, check that there is nothing left in the remaining metar
        if ($result != null && $result['status'] == 'NIL') {
            if (strlen(trim($next_remaining_metar)) > 0) {
                throw new ChunkDecoderException($remaining_metar, 'No information expected after NIL status', $this);
            }
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $next_remaining_metar,
        );
    }
}
