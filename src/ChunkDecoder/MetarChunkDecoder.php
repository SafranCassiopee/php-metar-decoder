<?php

namespace MetarDecoder\ChunkDecoder;

abstract class MetarChunkDecoder
{
    /**
     * Extract the corresponding chunk from the remaining metar
     * @return matches array if any match (null if no match), + updated remaining metar
     */
    public function consume($remaining_metar)
    {
        $chunk_regexp = $this->getRegexp();
        
        // try to match chunk's regexp on remaining metar
            $found = $matches;
        }else{
            $found = null;
        }
    }

    /**
     * Build new remaining metar from current remaining metar
     * @return string original remaining metar amputed from the matched chunk
     */
    public function getRemainingMetar($remaining_metar)
    {
        return preg_replace($this->getRegexp(), '', $remaining_metar, 1);
    }
}
