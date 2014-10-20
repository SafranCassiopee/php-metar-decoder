<?php

namespace MetarDecoder\Entity;

use \DateTime;

class DecodedMetar
{
    // raw METAR
    private $raw_metar;
    
    // report type (METAR, METAR COR or SPECI)
    private $type;
    
    // ICAO code of the airport where the observation has been made
    private $icao;
    
    // day of this observation
    private $day;
    
    // time of the observation
    private $time;
    
    public function __construct($raw_metar)
    {
        $this->raw_metar=$raw_metar;
        $this->type = null;
        $this->icao = null;
        $this->day = null;
        $this->time = null;
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
    
}
