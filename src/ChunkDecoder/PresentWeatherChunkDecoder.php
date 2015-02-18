<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Entity\PresentWeather;
use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for present weather section
 */
class PresentWeatherChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    private $pre_dic = array(
        'DZ', 'RA', 'SN', 'SG',
        'PL', 'DS', 'SS', 'FZDZ',
        'FZRA', 'FZUP', 'FC', 'SHGR',
        'SHGS', 'SHRA', 'SHSN', 'SHUP',
        'TSGR', 'TSGS', 'SHRA', 'SHSN',
        'SHUP', 'TSGR', 'TSGS', 'TSRA',
        'TSSN', 'TSUP', 'UP', 'IC',
    );
    private $obs_dic = array(
        'FG', 'BR', 'SA', 'DU',
        'HZ', 'FU', 'VA', 'SQ',
        'PO', 'TS', 'BCFG', 'BLDU',
        'BLSA', 'BLSN', 'DRDU', 'DRSA',
        'DRSN', 'FZFG', 'MIFG', 'PRFG',
        '//',
    );
    private $vic_dic = array(
        'VCFG', 'VCPO', 'VCFC', 'VCDS',
        'VCSS', 'VCTS', 'VCSH', 'VCBLSN',
        'VCBLSA', 'VCBLDU', 'VCVA',
    );

    public function getRegexp()
    {
        return "#^([+-]?([A-Z]{2,6}|//) ){1,}#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            $result = null;
        } else {
            // manually split the string into phenomenons and group them by categories
            $precipitations = array();
            $obstacles = array();
            $vicinity = array();
            $present_weather = new PresentWeather();
            $weather_chunks = explode(' ', trim($found[0]));
            foreach ($weather_chunks as $chunk) {
                if (in_array(trim($chunk, '+-'), $this->pre_dic)) {
                    $precipitations[] = $chunk;
                    $present_weather->addPrecipitation($chunk);
                } elseif (in_array($chunk, $this->obs_dic)) {
                    $present_weather->addObstacle($chunk);
                } elseif (in_array($chunk, $this->vic_dic)) {
                    $present_weather->addVicinity(substr($chunk, 2));
                } else {
                    throw new ChunkDecoderException($remaining_metar, 'Bad format for present weather information, unknown weather phenomenon "'.$chunk.'"', $this);
                }
            }

            $result = array(
                'presentWeather' => $present_weather,
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
