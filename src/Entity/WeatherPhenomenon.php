<?php

namespace MetarDecoder\Entity;

class WeatherPhenomenon
{
    // intensity/proximity of the phenomenon + / - / VC (=vicinity)
    private $intensity_proximity;

    // characteristics of the phenomenon
    private $characteristics;

    // types of phenomenon
    private $types;

    public function getIntensityProximity()
    {
        return $this->intensity_proximity;
    }

    public function getCharacteristics()
    {
        return $this->characteristics;
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function setIntensityProximity($intensity_proximity)
    {
        $this->intensity_proximity = $intensity_proximity;

        return $this;
    }

    public function setCharacteristics($carac)
    {
        $this->characteristics = $carac;
    }
    public function addType($phenomenon)
    {
        $this->types[] = $phenomenon;

        return $this;
    }
}
