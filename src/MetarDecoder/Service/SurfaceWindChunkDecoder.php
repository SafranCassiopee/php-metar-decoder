<?php

namespace MetarDecoder\Service;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\SurfaceWind;

/**
 * Chunk decoder for surface wind section
 */
class SurfaceWindChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    
    public function getRegexp()
    {
        $direction = "([/0-9]{3}|V?RB)";
        $speed ="(P?[/0-9]{2}[0-9]?)";
        $speed_variations = "(GP?([0-9]{2}[0-9]?))?"; // optionnal
        $unit = "(KT|MPS)";
        $direction_variations = "( ([0-9]{3})V([0-9]{3}))?"; // optionnal
        
        return "#^$direction$speed$speed_variations$unit$direction_variations( )#";
        //last group capture is here to ensure that array will always have the same size if there is a match
    }
    
    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        
        // handle the case where nothing has been found
        if($found == null){
            throw new ChunkDecoderException($remaining_metar, 'Bad format for surface wind information, applied regexp is "'.$this->getRegexp().'"', $this);
        }
                
        // retrieve found params
        $surface_wind = new SurfaceWind();
        $surface_wind->setDirection($found[1])
                     ->setSpeed($found[2])
                     ->setSpeedVariations($found[4])
                     ->setSpeedUnit($found[5])
                     ->setDirectionVariations($found[7], $found[8]);
                
        // return result + remaining metar
        return array(
            'result' => array( 'surfaceWind' => $surface_wind ),
            'remaining_metar' => $this->getRemainingMetar($remaining_metar)
        );
    }
}
