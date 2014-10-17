<?php

namespace MetarDecoder\Entity;

class DecodedMetar
{
    // raw METAR
    private $raw_metar;
    
    // ICAO code of the airport where the observation has been made
    private $icao;
    
    // Date and time of the observation
    private $datetime;
    
    public function __construct($raw_metar)
    {
        $this->raw_metar=$raw_metar;
        $this->icao = '';
        $this->datetime = '';
    }
    
    public function getRawMetar()
    {
        return $this->raw_metar;
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
    
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }
    
    public function getDatetime()
    {
        return $this->datetime;
    }
    
}
