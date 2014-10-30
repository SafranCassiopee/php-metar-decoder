<?php

namespace MetarDecoder\ChunkDecoder;

/**
 * Chunk decoder for report type section
 */
class ReportTypeChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    
    public function getRegexp()
    {
        return '#^((METAR|SPECI){1}( COR){0,1}) #';
    }
    
    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        
        // handle the case where nothing has been found
        if($found == null){
            $result = null;
        }else{// retrieve found params
            $result = array(
                'type' => $found[1]
            );
        }
        
        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar)
        );
    }
}
