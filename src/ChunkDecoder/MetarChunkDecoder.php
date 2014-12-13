<?php

namespace MetarDecoder\ChunkDecoder;

abstract class MetarChunkDecoder
{
    /**
     * Apply regexp on remaining metar string
     * @return array matches array if any match, null if no match
     */
    public function applyRegexp($remaining_metar)
    {
        // match regexp on remaining metar string and return matches if any
        if (preg_match($this->getRegexp(), $remaining_metar, $matches)) {
            return $matches;
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
