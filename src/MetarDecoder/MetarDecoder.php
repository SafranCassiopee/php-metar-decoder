<?php

namespace MetarDecoder;

use MetarDecoder\Entity\DecodedMetar;
use MetarDecoder\Service\ReportTypeChunkDecoder;
use MetarDecoder\Service\IcaoChunkDecoder;
use MetarDecoder\Service\DatetimeChunkDecoder;

class MetarDecoder
{

    private $decoder_chain;
    
    public function __construct()
    {
        $this->decoder_chain = array(
            new ReportTypeChunkDecoder(),
            new IcaoChunkDecoder(),
            new DatetimeChunkDecoder()
        );
    }

    /**
     * Decode a full metar string
     * Under construction
     */
    public function parse($raw_metar)
    {
        // init the parsing process
        $raw_metar_upper = strtoupper($raw_metar);
        $decoded_metar = new DecodedMetar($raw_metar_upper);
        $remaining_metar = $raw_metar_upper;
        
        // call each decoder in the chain and use results to populate decoded
        foreach($this->decoder_chain as $chunk_decoder){
            $decoded = $chunk_decoder->parse($remaining_metar);
            
            // handle the case where the decoding went wrong
            $result = $decoded['result'];
            if($result == null){
                if( $chunk_decoder->isMandatory()){
                    throw new \Exception('Parsing error for '.get_class($chunk_decoder).': "'.$remaining_metar.'"');
                }else{
                    $result = array();
                }
            }else{
                // map obtained fields to the final decoded object
                foreach($result as $key => $value){
                    $setter_name = 'set'.ucfirst($key);
                    $decoded_metar->$setter_name($value);
                }
            }
            
            // get new remaining metar
            $remaining_metar = $decoded['remaining_metar'];
        }
        
        return $decoded_metar;
    }


}
