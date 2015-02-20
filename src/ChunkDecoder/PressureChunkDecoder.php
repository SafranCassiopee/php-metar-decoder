<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for atmospheric pressure section
 */
class PressureChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    private $units = array(
        'Q' => 'hPA',
        'A' => 'inHg',
    );

    public function getRegexp()
    {
        return "#^(Q|A)(////|[0-9]{4})( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // throw error if nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar, 'Atmospheric pressure not found', $this);
        }

        // get unit and make a conversion if needed
        $type = $found[1];
        $unit = $this->units[$type];
        $value = $this->toInt($found[2]);
        if ($type == 'A') {
            $value = $value / 10;
        }

        // retrieve found params
        $result = array(
            'pressure' => $value,
            'pressureUnit' => $unit,
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
