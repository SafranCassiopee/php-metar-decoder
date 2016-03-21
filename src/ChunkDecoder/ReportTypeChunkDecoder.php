<?php

namespace MetarDecoder\ChunkDecoder;

/**
 * Chunk decoder for report type section.
 */
class ReportTypeChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        return '#^((METAR|SPECI)( COR){0,1}) #';
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
            // retrieve found params
            $result = array(
                'type' => $found[1],
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
