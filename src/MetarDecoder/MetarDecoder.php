<?php

namespace MetarDecoder;

use MetarDecoder\Entity\DecodedMetar;
use MetarDecoder\Service\IcaoChunkDecoder;

class MetarDecoder
{

    private $decoder_chain;
    
    public function __construct()
    {
        $this->decoder_chain = array(
            new IcaoChunkDecoder()
        );
    }

    /**
     * Decode a full metar string
     * Under construction
     */
    public function parse($raw_metar)
    {
        // init the parsing process
        $decoded_metar = new DecodedMetar($raw_metar);
        $remaining_metar = $raw_metar;
        
        // call each decoder in the chain and use results to populate decoded
        foreach($this->decoder_chain as $chunk_decoder){
            $decoded = $chunk_decoder->parse($remaining_metar);
            
            // handle the case where the decoding went wrong
            $result = $decoded['result'];
            if($result == null && $chunk_decoder->isMandatory()){
                throw new \Exception('Parsing error for '.get_class($chunk_decoder).': "'.$remaining_metar.'"');
            }
            
            // map obtained fields to the final decoded object
            foreach($result as $key => $value){
                $setter_name = 'set'.ucfirst($key);
                $decoded_metar->$setter_name($value);
            }

            // get new remaining metar
            $remaining_metar = $decoded['remaining_metar'];
        }
        
        return $decoded_metar;
    }


}
