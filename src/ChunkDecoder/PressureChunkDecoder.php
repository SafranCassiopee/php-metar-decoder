<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\Value;

/**
 * Chunk decoder for atmospheric pressure section.
 */
class PressureChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    private $units = array(
        'Q' => Value::HECTO_PASCAL,
        'A' => Value::MERCURY_INCH,
    );

    public function getRegexp()
    {
        return '#^(Q|A)(////|[0-9]{4})( )#';
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
                                            'Atmospheric pressure not found',
                                            $this);
        }

        $raw_value = Value::toInt($found[2]);
        $type = $found[1];
        // convert value if needed
        if ($type == 'A') {
            $raw_value = $raw_value / 100;
        }
        $value = Value::newValue($raw_value, $this->units[$type]);

        // retrieve found params
        $result = array(
            'pressure' => $value,
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
