<?php

namespace MetarDecoder\Entity;

class SurfaceWind
{
    // wind direction
    private $mean_direction;

    // wind variability (if true, direction is null)
    private $variable_direction;

    // wind speed
    private $mean_speed;

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

    public function getMeanDirection()
    {
        return $this->mean_direction;
    }

    public function setMeanDirection($direction)
    {
        $this->mean_direction = $direction;

        return $this;
    }

    public function getMeanSpeed()
    {
        return $this->mean_speed;
    }

    public function setMeanSpeed($speed)
    {
        $this->mean_speed = $speed;

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
