<?php

namespace MetarDecoder\Exception;

class ChunkDecoderException extends \Exception
{
    private $metar_chunk;

    private $chunk_decoder_class;

    public function __construct($metar_chunk, $message, $chunk_decoder)
    {
        parent::__construct($message.' for chunk "'.$metar_chunk.'"');
        $this->metar_chunk = $metar_chunk;
        $this->chunk_decoder_class = (new \ReflectionClass($chunk_decoder))->getShortName();
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
}
