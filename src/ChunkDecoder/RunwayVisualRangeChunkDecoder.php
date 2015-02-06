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
        $runway="R([0-9]{2}[LCR]?)/([PM]?[0-9]{4})([UDN]?)";
        return "#^($runway)( $runway)?( $runway)?( $runway)?( )#";
    }

    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        var_dump($found);
        
        // throw error if nothing has been found
        if ($found == null) {
            // TODO no error in the case of CAVOK
            throw new ChunkDecoderException($remaining_metar, 'Runway visual range not found', $this);
        }
    
        // iterate on the results to get all runways visual range found
        $runways = array();
        for($i = 1; $i <= 16 ; $i+= 4 ){
            if($found[$i] != null){
                $observation = new RunwayVisualRange();
                $observation->setRunway($found[$i+1])
                            ->setVisualRange($found[$i+2])
                            ->setPastTendency($found[$i+3]);
                $runways[] = $observation;
            }
        }
        
        // return result + remaining metar
        return array(
            'result' => array(
                'runways_visual_range' => $runways,
            ),
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
