<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\Visibility;
use MetarDecoder\Entity\Value;

/**
 * Chunk decoder for visibility section.
 */
class VisibilityChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $cavok = 'CAVOK';
        $visibility = '([0-9]{4})(NDV)?';
        $us_visibility = 'M?([0-9]{0,2}) ?(([1357])/(2|4|8|16))?SM';
        $minimum_visibility = '( ([0-9]{4})(N|NE|E|SE|S|SW|W|NW)?)?';// optional
        $no_info = '////';

        return "#^($cavok|$visibility$minimum_visibility|$us_visibility|$no_info)( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $result = $this->consume($remaining_metar);
        $found = $result['found'];
        $new_remaining_metar = $result['remaining'];

        // handle the case where nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar,
                                            $new_remaining_metar,
                                            'Bad format for visibility information',
                                            $this);
        }
        if ($found[1] ==  'CAVOK') { // cloud and visibility OK
            $cavok = true;
            $visibility = null;
        } elseif ($found[1] == '////') { // information not available
            $cavok = false;
            $visibility = null;
        } else {
            $cavok = false;
            $visibility = new Visibility();
            if ($found[2] != null) { // icao visibility
                $visibility->setVisibility(Value::newIntValue($found[2], Value::METER));
                if ($found[4] != null) {
                    $visibility->setMinimumVisibility(Value::newIntValue($found[5], Value::METER))
                                ->setMinimumVisibilityDirection($found[6]);
                }
                $visibility->setNDV($found[3] != null);
            } else { // us visibility
                $main = intval($found[7]);
                $frac_top = intval($found[9]);
                $frac_bot = intval($found[10]);
                if ($frac_bot != 0) {
                    $vis_value = $main + $frac_top / $frac_bot;
                } else {
                    $vis_value = $main;
                }
                $visibility->setVisibility(Value::newValue($vis_value, Value::STATUTE_MILE));
            }
        }

        // return result + remaining metar
        return array(
            'result' => array(
                'cavok' => $cavok,
                'visibility' => $visibility,
            ),
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
