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
     * Get the class of the chunk decoder which triggered the exception.
     */
    public function getChunkDecoder()
    {
        return $this->chunk_decoder_class;
    }

    /**
     * Get metar chunk that failed during decoding.
     */
    public function getChunk()
    {
        return $this->metar_chunk;
    }

    /**
     * Get remaining metar after the chunk decoder consumed it
     * Having this information can allow the decoding to continue.
     */
    public function getRemainingMetar()
    {
        return $this->remaining_metar;
    }
}
