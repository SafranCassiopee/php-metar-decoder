<?php

namespace MetarDecoder\Entity;

class RunwayVisualRange
{
    // concerned runway
    private $runway;

    // visual range
    private $visual_range;

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
