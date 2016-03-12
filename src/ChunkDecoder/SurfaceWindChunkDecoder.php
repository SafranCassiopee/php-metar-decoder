<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\SurfaceWind;
use MetarDecoder\Entity\Value;

/**
 * Chunk decoder for surface wind section.
 */
class SurfaceWindChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $direction = '([0-9]{3}|VRB|///)';
        $speed = 'P?([/0-9]{2,3}|//)';
        $speed_variations = '(GP?([0-9]{2,3}))?'; // optionnal
        $unit = '(KT|MPS|KPH)';
        $direction_variations = '( ([0-9]{3})V([0-9]{3}))?'; // optionnal

        return "#^$direction$speed$speed_variations$unit$direction_variations( )#";
        //last group capture is here to ensure that array will always have the same size if there is a match
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
                                            'Bad format for surface wind information',
                                            $this);
        }

        // handle the case where nothing is observed
        if ($found[1] == '///' && $found[2] == '//') {
            throw new ChunkDecoderException($remaining_metar,
                                            $new_remaining_metar,
                                            'No information measured for surface wind',
                                            $this);
        }

        // get unit used
        switch ($found[5]) {
            case 'KT':
                $speed_unit = Value::KNOT;
                break;
            case 'KPH':
                $speed_unit = Value::KILOMETER_PER_HOUR;
                break;
            case 'MPS':
                $speed_unit = Value::METER_PER_SECOND;
                break;
        }

        // retrieve and validate found params
        $surface_wind = new SurfaceWind();

        // mean speed
        $surface_wind->setMeanSpeed(Value::newIntValue($found[2], $speed_unit));

        // mean direction
        if ($found[1] == 'VRB') {
            $surface_wind->setVariableDirection(true);
            $surface_wind->setMeanDirection(null);
        } else {
            $mean_dir = Value::newIntValue($found[1], Value::DEGREE);
            if ($mean_dir->getValue() < 0 || $mean_dir->getValue() > 360) {
                throw new ChunkDecoderException($remaining_metar,
                                                $new_remaining_metar,
                                                'Wind direction should be in [0,360]',
                                                $this);
            }
            $surface_wind->setVariableDirection(false);
            $surface_wind->setMeanDirection($mean_dir);
        }

        // direction variations
        if (strlen($found[7]) > 0) {
            $min_dir_var = Value::newIntValue($found[7], Value::DEGREE);
            $max_dir_var = Value::newIntValue($found[8], Value::DEGREE);
            if ($min_dir_var->getValue() < 0 || $min_dir_var->getValue() > 360
            || $max_dir_var->getValue() < 0 || $max_dir_var->getValue() > 360) {
                throw new ChunkDecoderException($remaining_metar,
                                                $new_remaining_metar,
                                                'Wind direction variations should be in [0,360]',
                                                $this);
            }
            $surface_wind->setDirectionVariations($min_dir_var, $max_dir_var);
        }

        // speed variations
        if (strlen($found[4]) > 0) {
            $surface_wind->setSpeedVariations(Value::newIntValue($found[4], $speed_unit));
        }

        // return result + remaining metar
        return array(
            'result' => array('surfaceWind' => $surface_wind),
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
