<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\Value;

/**
 * Chunk decoder for air and dew point temperature section
 */
class TemperatureChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $temp_regex = '(M?[0-9]{2})';

        return "#^$temp_regex/$temp_regex #";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // throw error if nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar, 'Air and dew point temperature not found', $this);
        }

        // retrieve found params
        $result = array(
            'airTemperature' => Value::newIntValue($found[1], Value::DEGREE_CELSIUS),
            'dewPointTemperature' => Value::newIntValue($found[2], Value::DEGREE_CELSIUS),
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
