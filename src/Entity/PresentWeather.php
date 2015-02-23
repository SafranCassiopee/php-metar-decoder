<?php

namespace MetarDecoder\Entity;

class PresentWeather
{
    // precipitations phenomenon
    private $precipitations;

    // obscurations phenomenon
    private $obscurations;

    // vicinity phenomenon
    private $vicinities;

    public function getPrecipitations()
    {
        return $this->precipitations;
    }

    public function addPrecipitation($precipitation_phenomenon)
    {
        $this->precipitations[] = $precipitation_phenomenon;

        return $this;
    }

    public function getObscurations()
    {
        return $this->obscurations;
    }

    public function addObscuration($obscuration_phenomenon)
    {
        $this->obscurations[] = $obscuration_phenomenon;

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
