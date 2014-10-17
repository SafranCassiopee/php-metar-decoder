<?php

namespace MetarDecoder\Service;

class IcaoChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    
    public function isMandatory()
    {
        return true;
    }
    
    public function getRegexp()
    {
        return '#^([A-Z0-9]{4}) #';
    }
    
    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        
        // handle the case where nothing has been found
        if($found == null){
            $result = null;
        }else{// retrieve found params
            $result = array(
                'icao' => $found[1]
            );
        }
        
        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar)
        );
    }
}
