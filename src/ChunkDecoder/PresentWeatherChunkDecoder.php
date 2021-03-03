<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Entity\WeatherPhenomenon;

/**
 * Chunk decoder for present weather section.
 */
class PresentWeatherChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public static $carac_dic = array(
        'TS', 'FZ', 'SH', 'BL', 'DR', 'MI', 'BC', 'PR',
    );
    public static $type_dic = array(
        'DZ', 'RA', 'SN', 'SG',
        'PL', 'DS', 'GR', 'GS',
        'UP', 'IC', 'FG', 'BR',
        'SA', 'DU', 'HZ', 'FU',
        'VA', 'PY', 'DU', 'PO',
        'SQ', 'FC', 'DS', 'SS',
    );

    public function getRegexp()
    {
        $carac_regexp = implode('|', self::$carac_dic);
        $type_regexp = implode('|', self::$type_dic);
        $pw_regexp = "([-+]|VC)?($carac_regexp)?($type_regexp)?($type_regexp)?($type_regexp)?";

        return "#^($pw_regexp )?($pw_regexp )?($pw_regexp )?(// )?()?#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $result = $this->consume($remaining_metar);
        $found = $result['found'];
        $new_remaining_metar = $result['remaining'];

        $present_weather = array();
        for ($i = 1; $i <= 13; $i += 6) {
            if ($found[$i] != null && $found[$i + 3] != '//') {
                $weather = new WeatherPhenomenon();
                $weather->setIntensityProximity($found[$i + 1]);
                $weather->setCharacteristics($found[$i + 2]);
                for ($k = 3; $k <= 5; ++$k) {
                    if ($found[$i + $k] != null) {
                        $weather->addType($found[$i + $k]);
                    }
                }
                $present_weather[] = $weather;
            }
        }
        $result = array(
            'presentWeather' => $present_weather,
        );

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
