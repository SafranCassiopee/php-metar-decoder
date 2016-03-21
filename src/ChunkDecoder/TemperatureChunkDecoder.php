<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Entity\Value;

/**
 * Chunk decoder for air and dew point temperature section.
 */
class TemperatureChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $temp_regex = '(M?[0-9]{2})';

        return "#^$temp_regex/$temp_regex?( )#";
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
            $air_temp = Value::newIntValue($found[1], Value::DEGREE_CELSIUS);
            if ($found[2] == null) {
                $dew_point_temp = null;
            } else {
                $dew_point_temp = Value::newIntValue($found[2], Value::DEGREE_CELSIUS);
            }

            $result = array(
                'airTemperature' => $air_temp,
                'dewPointTemperature' => $dew_point_temp,
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
