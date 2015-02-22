<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\Value;

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

        $raw_value = Value::toInt($found[2]);
        $type = $found[1];
        // convert value if needed
        if ($type == 'A') {
            $raw_value = $raw_value / 10;
        }
        $value = new Value($raw_value, $this->units[$type]);

        // retrieve found params
        $result = array(
            'pressure' => $value,
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
