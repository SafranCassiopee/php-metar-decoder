<?php

namespace MetarDecoder\ChunkDecoder;

abstract class MetarChunkDecoder
{
    
    /**
     * Apply regexp on remaining metar string
     * @return matches array if any match, null if no match
     */
    public function applyRegexp($remaining_metar)
    {        
        // match regexp on remaining metar string and return matches if any
        if (preg_match($this->getRegexp(), $remaining_metar, $matches)) {
            return $matches;
        }else{
            return null;
        }
    }
    
    /**
     * Build new remaining metar from current remaining metar
     * @return original remaining metar amputed from the matched chunk
     */
    public function getRemainingMetar($remaining_metar)
    {
        return preg_replace($this->getRegexp(), '', $remaining_metar, 1);
    }
    
}
