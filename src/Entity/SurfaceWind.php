<?php

namespace MetarDecoder\Entity;

class SurfaceWind
{
    // wind direction
    private $direction;

    // wind variability (if true, direction is null)
    private $variable_direction;

    // wind speed
    private $speed;

    // wind speed variation (gusts)
    private $speed_variations;

    // boundaries for wind direction variation
    private $direction_variations;

    public function withVariableDirection()
    {
        return $this->variable_direction;
    }

    public function setVariableDirection($is_variable)
    {
        $this->variable_direction = $is_variable;
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

    public function getDirectionVariations()
    {
        return $this->direction_variations;
    }

    public function setDirectionVariations($direction_max, $direction_min)
    {
        if ($direction_max != null && $direction_min != null) {
            $this->direction_variations = array($direction_max, $direction_min);
        }

        return $this;
    }
}
