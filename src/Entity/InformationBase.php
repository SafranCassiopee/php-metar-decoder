<?php

namespace MetarDecoder\Entity;


class InformationBase
{
    // chunk detected for decoding
    private $chunk;

    public function getChunk()
    {
        return $this->chunk;
    }

    public function setChunk($chunk)
    {
        $this->chunk = $chunk;
    }
}
