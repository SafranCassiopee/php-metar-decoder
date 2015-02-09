<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\RunwayVisualRange;

/**
 * Chunk decoder for runway visual range section
 */
class RunwayVisualRangeChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $runway = "R([0-9]{2}[LCR]?)/([PM]?[0-9]{4})([UDN]?)";

        return "#^($runway)( $runway)?( $runway)?( $runway)?( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            // if cavok has been detected earlier in the metar, no problem
            if ($cavok) {
                $result = null;
            } else {
                throw new ChunkDecoderException($remaining_metar, 'Runway visual range not found', $this);
            }
        } else {
            // iterate on the results to get all runways visual range found
            $runways = array();
            for ($i = 1; $i <= 16; $i += 4) {
                if ($found[$i] != null) {
                    $observation = new RunwayVisualRange();
                    $observation->setRunway($found[$i+1])
                                ->setVisualRange($found[$i+2])
                                ->setPastTendency($found[$i+3]);
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
