<?php

namespace MetarDecoder\Entity;

class WeatherPhenomenon
{
    // intensity/proximity of the phenomenon + / - / VC (=vicinity)
    private $intensity_proximity;

    // caracterisation of phenomenon
    private $caracterisation;

    // types of phenomenon
    private $types;

    public function getIntensityProximity()
    {
        return $this->intensity_proximity;
    }

    public function getCaracterisation()
    {
        return $this->caracterisation;
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
