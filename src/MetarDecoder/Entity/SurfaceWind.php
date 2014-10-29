<?php

namespace MetarDecoder\Entity;

class SurfaceWind
{
    // wind direction
    private $direction;
    
    // wind speed
    private $speed;
    
    // wind speed variation (gusts)
    private $speed_variations;
    
    // speed unit (KT or MPS)
    private $speed_unit;
    
    // boundaries for wind direction variation
    private $direction_variations;
    

    public function __construct()
    {

    }
    
    public function getDirection()
    {
        return $this->direction;
    }
    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }
    
    public function getSpeed()
    {
        return $this->speed;
    }
    public function setSpeed($speed)
    {
        $this->speed = $speed;
        return $this;
    }
    
    public function getSpeedVariations()
    {
        return $this->speed_variations;
    }
    public function setSpeedVariations($speed_variations)
    {
        $this->speed_variations = $speed_variations;
        return $this;
    }
    
    public function getSpeedUnit()
    {
        return $this->speed_unit;
    }
    public function setSpeedUnit($unit)
    {
        $this->speed_unit = $unit;
        return $this;
    }
    
    public function getDirectionVariations()
    {
        return $this->direction_variations;
    }
    public function setDirectionVariations($direction_max, $direction_min)
    {
        $this->direction_variations = array($direction_max, $direction_min);
        return $this;
    }

   
}
