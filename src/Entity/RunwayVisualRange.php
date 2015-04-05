<?php

namespace MetarDecoder\Entity;

class RunwayVisualRange
{
    // concerned runway
    private $runway;

    // visual range defined by one value
    private $visual_range;

    // or visual range defined by an interval (because it is variable)
    private $visual_range_interval;

    // is it a variable range ?
    private $variable;

    // past tendency (optionnal) (U,D, or N)
    private $past_tendency;

    public function getRunway()
    {
        return $this->runway;
    }

    public function setRunway($runway)
    {
        $this->runway = $runway;

        return $this;
    }

    public function getVisualRange()
    {
        return $this->visual_range;
    }

    public function setVisualRange($visual_range)
    {
        $this->visual_range = $visual_range;

        return $this;
    }

    public function getVisualRangeInterval()
    {
        return $this->visual_range_interval;
    }

    public function setVisualRangeInterval($value_interval)
    {
        $this->visual_range_interval = $value_interval;

        return $this;
    }

    public function isVariable()
    {
        return $this->variable;
    }

    public function setVariable($variable)
    {
        $this->variable = $variable;

        return $this;
    }

    public function getPastTendency()
    {
        return $this->past_tendency;
    }

    public function setPastTendency($past_tendency)
    {
        $this->past_tendency = $past_tendency;

        return $this;
    }
}
