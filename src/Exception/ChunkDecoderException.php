<?php

namespace MetarDecoder\Exception;

class ChunkDecoderException extends \Exception
{
    private $metar_chunk;

    private $remaining_metar;

    private $chunk_decoder_class;

    public function __construct($metar_chunk, $remaining_metar, $message, $chunk_decoder)
    {
        parent::__construct($message);
        $this->metar_chunk = trim($metar_chunk);
        $this->remaining_metar = $remaining_metar;
        $r_class = new \ReflectionClass($chunk_decoder);
        $this->chunk_decoder_class = $r_class->getShortName();
    }

    /**
     * Get the class of the chunk decoder which triggered the exception
     */
    public function getChunkDecoder()
    {
        return $this->chunk_decoder_class;
    }

    /**
     * Get metar chunk that failed during decoding
     */
    public function getChunk()
    {
        return $this->metar_chunk;
    }

    /**
     * Get remaining metar after the chunk decoder consumed it
     * In the cases where the exception is triggered because
      * chunk's regexp didn't match, one chunk will be eaten
      * with whitespace separator.
      * Having this information can allow the decoding to continue
      */
    public function getFreshRemainingMetar()
    {
        if(trim($this->remaining_metar) == $this->metar_chunk){
          return $this->consumeOneChunk($this->remaining_metar);
        } else {
          return $this->remaining_metar;
        }
    }


    /**
     * Consume one chunk blindly, without looking for the specific pattern
     * (only whitespace)
     */
    private static function consumeOneChunk($remaining_metar)
    {
        $next_space = strpos($remaining_metar, ' ');
        if($next_space > 0){
            return substr($remaining_metar, $next_space + 1);
        } else {
            return $remaining_metar;
        }

    }
}
