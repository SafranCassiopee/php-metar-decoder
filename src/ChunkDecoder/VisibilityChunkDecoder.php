<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\Visibility;
use MetarDecoder\Entity\Value;

/**
 * Chunk decoder for visibility section
 */
class VisibilityChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $cavok = "CAVOK";
        $visibility = "([0-9]{4})";
        $us_visibility = "M?([0-9]{0,2}) ?(([1357])/(2|4|8|16))?SM";
        $minimum_visibility = "( ([0-9]{4})(N|NE|E|SE|S|SW|W|NW)?)?";// optional
        return "#^($cavok|$visibility$minimum_visibility|$us_visibility)( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);
        
        // handle the case where nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar, 'Bad format for visibility information', $this);
        }

        if ($found[1] ==  'CAVOK') {
            // handle the CAVOK case
            $cavok = true;
            $visibility = null;
        } else {
            $cavok = false;
            $visibility = new Visibility();
            if($found[2] != null) { // icao visibility
                $visibility->setVisibility(Value::newIntValue($found[2], Value::METER))
                           ->setMinimumVisibility(Value::newIntValue($found[4], Value::METER))
                           ->setMinimumVisibilityDirection($found[5]);
            }else{ // us visibility
                //var_dump($found);
                $main = intval($found[6]);
                $frac_top = intval($found[8]);
                $frac_bot = intval($found[9]);
                if($frac_bot != 0){
                    $vis_value = $main + $frac_top / $frac_bot;
                }else{
                    $vis_value = $main;
                }
                
                //var_dump($vis_value);
                $visibility->setVisibility(Value::newValue($vis_value, Value::STATUTE_MILE));
            }
        }

        // return result + remaining metar
        return array(
            'result' => array(
                'cavok' => $cavok,
                'visibility' => $visibility,
            ),
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
