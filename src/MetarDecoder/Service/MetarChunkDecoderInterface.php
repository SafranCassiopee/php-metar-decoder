<?php

namespace MetarDecoder\Service;

interface MetarChunkDecoderInterface
{
   
    /**
     * Get mandatory / not mandatory status of the chunk for which the decoder is designed
     */
    public function isMandatory();
    
       
    /**
     * Get the regular expression that will be used by chunk decoder
     * Each chunk decoder must declare its own
     */
    public function getRegexp();
    
    
    /**
     * Decode the chunk targetted by the chunk decoder and returns the decoded information and the remaining metar without this chunk
     */
    public function parse($remaining_metar);
    
}
