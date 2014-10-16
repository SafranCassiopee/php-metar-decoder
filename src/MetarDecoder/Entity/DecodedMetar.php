<?php

namespace MetarDecoder\Entity;

class DecodedMetar
{
    // ICAO code of the airport where the observation has been made
    private $icao;
    
    // Date and time of the observation
    private $datetime;
    
    public function __construct()
    {
        $this->icao = '';
        $this->datetime = '';
    }
    
    /**
     * Set ICAO code
     */
    public function setIcao($icao)
    {
        $this->icao = $icao;
        return $this;
    }
    
    /**
     * Get ICAO code
     */
    public function getIcao()
    {
        return $this->icao;
    }
    
    /**
     * Set observation datetime
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;
        return $this;
    }
    
   /**
     * Get observation datetime
     */
    public function getDatetime()
    {
        return $this->datetime;
    }
    
}
