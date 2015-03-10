<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Entity\RunwayVisualRange;
use MetarDecoder\Entity\Value;
use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for runway visual range section
 */
class RunwayVisualRangeChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $runway = "R([0-9]{2}[LCR]?)/[PM]?([0-9]{4})(FT)?([UDN]?)";

        return "#^($runway)( $runway)?( $runway)?( $runway)?( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            $result = null;
        } else {
            // iterate on the results to get all runways visual range found
            $runways = array();
            for ($i = 1; $i <= 20; $i += 5) {
                if ($found[$i] != null) {
                    // check runway qfu validity
                    $qfu_as_int = Value::toInt($found[$i+1]);
                    if ($qfu_as_int > 36 || $qfu_as_int < 1) {
                        throw new ChunkDecoderException($remaining_metar, 'Invalid runway QFU runway visual range information', $this);
                    }
                    // get distance unit
                    if ($found[$i+3] == "FT") {
                        $range_unit = Value::FEET;
                    } else {
                        $range_unit = Value::METER;
                    }
                    $observation = new RunwayVisualRange();
                    $observation->setRunway($found[$i+1])
                                ->setVisualRange(Value::newIntValue($found[$i+2], $range_unit))
                                ->setPastTendency($found[$i+4]);
                    $runways[] = $observation;
                }
            }
            $result = array('runwaysVisualRange' => $runways);
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
