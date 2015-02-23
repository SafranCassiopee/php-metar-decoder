<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\SurfaceWind;
use MetarDecoder\Entity\Value;

/**
 * Chunk decoder for surface wind section
 */
class SurfaceWindChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $direction = "([/0-9]{3}|V?RB)";
        $speed = "(P?[0-9]{2,3})";
        $speed_variations = "(GP?([0-9]{2,3}))?"; // optionnal
        $unit = "(KT|MPS)";
        $direction_variations = "( ([0-9]{3})V([0-9]{3}))?"; // optionnal

        return "#^$direction$speed$speed_variations$unit$direction_variations( )#";
        //last group capture is here to ensure that array will always have the same size if there is a match
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar, 'Bad format for surface wind information, applied regexp is "'.$this->getRegexp().'"', $this);
        }
        
        // get unit used
        if($found[5] == 'KT'){
            $speed_unit = Value::KNOT;
        }else{
            $speed_unit = Value::METER_PER_SECOND;
        }
        
        
        // retrieve found params
        $surface_wind = new SurfaceWind();
        if($found[1] == 'VRB' || $found[1] == 'RB'){
            $surface_wind->setVariableDirection(true);
            $surface_wind->setDirection(null);
        }else{
            $surface_wind->setVariableDirection(false);
            $surface_wind->setDirection(Value::newIntValue($found[1],Value::DEGREE));
        }
        $surface_wind->setDirectionVariations(Value::newIntValue($found[7],Value::DEGREE), Value::newIntValue($found[8],Value::DEGREE))
                     ->setSpeed(Value::newIntValue($found[2],$speed_unit))
                     ->setSpeedVariations(Value::newIntValue($found[4], $speed_unit));

        // return result + remaining metar
        return array(
            'result' => array( 'surfaceWind' => $surface_wind ),
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
