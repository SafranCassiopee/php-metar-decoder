<?php

namespace MetarDecoder\Entity;

class PresentWeather
{
    // precipitations weather
    private $precipitations;

    // obstacle weather
    private $obstacles;

    // vicinity weather
    private $vicinities;

    public function getPrecipitations()
    {
        return $this->precipitations;
    }

    public function addPrecipitation($precipitation)
    {
        $this->precipitations[] = $precipitation;

        return $this;
    }

    public function getObstacles()
    {
        return $this->obstacles;
    }

    public function addObstacle($obstacle)
    {
        $this->obstacles[] = $obstacle;

        return $this;
    }

    public function getVicinities()
    {
        return $this->vicinities;
    }

    public function addVicinity($vicinity)
    {
        $this->vicinities[] = $vicinity;

        return $this;
    }
}
