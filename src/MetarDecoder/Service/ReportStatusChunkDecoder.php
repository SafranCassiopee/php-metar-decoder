<?php

namespace MetarDecoder\Service;

/**
 * Chunk decoder for report statuc section (NIL or AUTO)
 */
class ReportStatusChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    
    public function getRegexp()
    {
        return '#^((AUTO|NIL){0,1}) #';
    }
    
    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        
        // handle the case where nothing has been found
        if($found == null){
            $result = null;
        }else{// retrieve found params
            $result = array(
                'status' => $found[1]
            );
        }
        
        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar)
        );
    }
}
