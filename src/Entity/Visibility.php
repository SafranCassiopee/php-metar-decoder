<?php

namespace MetarDecoder\Entity;

class Visibility
{
    // prevailing visibility
    private $visibility;

    // minimum visibility
    private $minimum;

    // direction of minimum visibility
    private $minimum_direction;

    // No Directional Variation
    private $ndv;

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getMinimumVisibility()
    {
        return $this->minimum;
    }

    public function setMinimumVisibility($minimum)
    {
        $this->minimum = $minimum;

        return $this;
    }

    public function getMinimumVisibilityDirection()
    {
        return $this->minimum_direction;
    }

    public function setMinimumVisibilityDirection($minimum_direction)
    {
        $this->minimum_direction = $minimum_direction;

        return $this;
    }

    public function hasNDV()
    {
        return $this->ndv;
    }

    public function setNDV($has_ndv)
    {
        $this->ndv = $has_ndv;
    }
}
