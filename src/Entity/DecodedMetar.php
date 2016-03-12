<?php

namespace MetarDecoder\Entity;

class DecodedMetar
{
    // raw METAR
    private $raw_metar;

    // decoding exceptions, if any
    private $decoding_exceptions = array();

    // report type (METAR, METAR COR or SPECI)
    private $type;

    // ICAO code of the airport where the observation has been made
    private $icao;

    // day of this observation
    private $day;

    // time of the observation, as a string
    private $time;

    // report status (AUTO or NIL)
    private $status;

    // surface wind information
    private $surface_wind;

    // visibility information
    private $visibility;
    private $cavok;

    // runway visual range information
    private $runways_visual_range;

    // present weather
    private $present_weather;

    // cloud layers information
    private $clouds;

    // temperature information
    private $air_temperature;
    private $dew_point_temperature;

    // pressure information
    private $pressure;

    // recent weather
    private $recent_weather;

    // windshear runway information (which runways, or "all")
    private $windshear_all_runways;
    private $windshear_runways;

    public function __construct($raw_metar)
    {
        $this->raw_metar = $raw_metar;

        $this->cavok = false;

        $this->decoding_exceptions = array();
    }

    /**
     * Check if the decoded metar is valid, i.e. if there was no error during decoding.
     */
    public function isValid()
    {
        return count($this->decoding_exceptions) == 0;
    }

    /**
     * Add an exception that occured during metar decoding.
     */
    public function addDecodingException($exception)
    {
        $this->decoding_exceptions[] = $exception;

        return $this;
    }

    /**
     * If the decoded metar is invalid, get all the exceptions that occurred during decoding
     * Note that in strict mode, only the first encountered exception will be reported as parsing stops on error
     * Else return null;.
     */
    public function getDecodingExceptions()
    {
        return $this->decoding_exceptions;
    }

    public function resetDecodingExceptions()
    {
        $this->decoding_exceptions = array();
    }

    public function getRawMetar()
    {
        return trim($this->raw_metar);
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

    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setSurfaceWind(SurfaceWind $surface_wind)
    {
        $this->surface_wind = $surface_wind;

        return $this;
    }

    public function getSurfaceWind()
    {
        return $this->surface_wind;
    }

    public function setVisibility(Visibility $visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function setCavok($cavok)
    {
        $this->cavok = $cavok;

        return;
    }

    public function getCavok()
    {
        return $this->cavok;
    }

    public function getRunwaysVisualRange()
    {
        return $this->runways_visual_range;
    }

    public function setRunwaysVisualRange(array $runways)
    {
        $this->runways_visual_range = $runways;

        return $this;
    }

    public function getPresentWeather()
    {
        return $this->present_weather;
    }

    public function setPresentWeather($weather)
    {
        $this->present_weather = $weather;

        return $this;
    }

    public function getClouds()
    {
        return $this->clouds;
    }

    public function setClouds(array $clouds)
    {
        $this->clouds = $clouds;

        return $this;
    }

    public function getAirTemperature()
    {
        return $this->air_temperature;
    }

    public function setAirTemperature($temperature)
    {
        $this->air_temperature = $temperature;
    }

    public function getDewPointTemperature()
    {
        return $this->dew_point_temperature;
    }

    public function setDewPointTemperature($temperature)
    {
        $this->dew_point_temperature = $temperature;

        return $this;
    }

    public function getPressure()
    {
        return $this->pressure;
    }

    public function setPressure($pressure)
    {
        $this->pressure = $pressure;
    }

    public function getRecentWeather()
    {
        return $this->recent_weather;
    }

    public function setRecentWeather($recent_weather)
    {
        $this->recent_weather = $recent_weather;

        return $this;
    }

    public function getWindshearAllRunways()
    {
        return $this->windshear_all_runways;
    }

    public function setWindshearAllRunways($all)
    {
        $this->windshear_all_runways = $all;

        return $this;
    }

    public function getWindshearRunways()
    {
        return $this->windshear_runways;
    }

    public function setWindShearRunways($runways)
    {
        $this->windshear_runways = $runways;

        return $this;
    }
}
