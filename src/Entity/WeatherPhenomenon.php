<?php

namespace MetarDecoder\Entity;

class WeatherPhenomenon
{
    // intensity/proximity of the phenomenon + / - / VC (=vicinity)
    private $intensity;

    // caracterisation of phenomenon
    private $caracterisation;

    // types of phenomenon
    private $types;

    public function getIntensity()
    {
        return $this->intensity;
    }

    public function getCaracterisation()
    {
        return $this->caracterisation;
    }

    public function getTypes()
    {
        return $this->types;
    }

    public function setIntensity($intensity)
    {
        $this->intensity = $intensity;

        return $this;
    }

    public function setCaracterisation($carac)
    {
        $this->caracterisation = $carac;
    }
    public function addType($phenomenon)
    {
        $this->types[] = $phenomenon;

        return $this;
    }
}
