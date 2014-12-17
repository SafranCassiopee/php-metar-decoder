<?php

namespace MetarDecoder\Entity;

use DateTime;
use MetarDecoder\Exception\ChunkDecoderException;

class DecodedMetar
{
    // raw METAR
    private $raw_metar;

    // decoding exception, if any
    private $decoding_exception;

    // report type (METAR, METAR COR or SPECI)
    private $type;

    // ICAO code of the airport where the observation has been made
    private $icao;

    // day of this observation
    private $day;

    // time of the observation
    private $time;

    // report status (AUTO or NIL)
    private $status;

    // surface wind information
    private $surface_wind;

    // visibility information
    private $visibility;
    private $cavok;

    // cloud information
    private $clouds;
    private $vertical_visibility;
    
    public function __construct($raw_metar)
    {
        $this->raw_metar = $raw_metar;
    }

    /**
     * Check if the decoded metar is valid, i.e. if there was no error during decoding
     */
    public function isValid()
    {
        return ($this->decoding_exception == null);
    }

    /**
     * Set the exception that occured during metar decoding
     */
    public function setException(ChunkDecoderException $exception)
    {
        $this->decoding_exception = $exception;

        return $this;
    }

    /**
     * If the decoded metar is invalid, get the exception that occured during decoding
     * Else return null;
     */
    public function getException()
    {
        return $this->decoding_exception;
    }

    public function getRawMetar()
    {
        return $this->raw_metar;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setIcao($icao)
    {
        $this->icao = $icao;

        return $this;
    }

    public function getIcao()
    {
        return $this->icao;
    }

    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    public function getDay()
    {
        return $this->day;
    }

    public function setTime(DateTime $time)
    {
        $this->time = $time;

        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setSurfaceWind(SurfaceWind $surface_wind)
    {
        $this->surface_wind = $surface_wind;

        return $this;
    }

    public function getSurfaceWind()
    {
        return $this->surface_wind;
    }

    public function setVisibility(Visibility $visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function setCavok($cavok)
    {
        $this->cavok = $cavok;

        return;
    }

    public function getCavok()
    {
        return $this->cavok;
    }
    
    public function getVerticalVisibility()
    {
        return $this->vertical_visibility;
    }

    public function setVerticalVisibility($vertical_visibility)
    {
        $this->vertical_visibility = $vertical_visibility;

        return $this;
    }
    
    public function getClouds()
    {
        return $this->clouds;
    }
    
    public function setClouds($clouds)
    {
        $this->clouds = $clouds;
        
        return $this;
    }
}
