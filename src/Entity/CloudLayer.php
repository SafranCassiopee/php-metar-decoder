<?php

namespace MetarDecoder\Entity;

class CloudLayer
{
    // annotation corresponding to amount of clouds (FEW/SCT/BKN/OVC)
    private $amount;

    // height of cloud base, unit ?
    private $base_height;

    // cloud type cumulonimbus, towering cumulonimbus (CB/TCU)
    private $type;

    public function __construct()
    {
        $this->amount = null;
        $this->base_height = null;
        $this->type = null;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBaseHeight()
    {
        return $this->base_height;
    }

    public function setBaseHeight($base_height)
    {
        $this->base_height = $base_height;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
