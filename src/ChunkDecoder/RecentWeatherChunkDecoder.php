<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Entity\WeatherPhenomenon;

/**
 * Chunk decoder for recent weather section
 */
class RecentWeatherChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $carac_regexp = implode(PresentWeatherChunkDecoder::$carac_dic, '|');
        $type_regexp = implode(PresentWeatherChunkDecoder::$type_dic, '|');     
        return "#^RE($carac_regexp)?($type_regexp)?($type_regexp)?($type_regexp)?()? #";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            $result = null;
        } else {
            // retrieve found params
            $weather = new WeatherPhenomenon();
            $weather->setCharacteristics($found[1]);
            for ($k = 2; $k <= 4; $k++) {
                if ($found[$k] != null) {
                    $weather->addType($found[$k]);
                }
            }

            $result = array(
                'recentWeather' => $weather,
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
