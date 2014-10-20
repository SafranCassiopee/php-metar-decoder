<?php

use MetarDecoder\Entity\DecodedMetar;
use \DateTime;
use \DateTimeZone;

class DecodedMetarTest extends PHPUnit_Framework_TestCase
{

    private $dataset;
    
    public function __construct()
    {
        $this->dataset= array(
            'raw_metar' => 'ABCD EFGH',
            'icao' => 'POLI',
            'day' => '26',
            'time' => DateTime::createFromFormat('H:i','09:47',new DateTimeZone('UTC'))
        );
    }
    
    public function testGetSet()
    {
        $s = $this->dataset;
        $d = new DecodedMetar($s['raw_metar']);
        $d->setIcao($s['icao'])
          ->setDay($s['day'])
          ->setTime($s['time']);
                     
        $this->assertEquals($s['icao'], $d->getIcao());
        $this->assertEquals($s['day'], $d->getDay());
        $this->assertEquals($s['time'], $d->getTime());
        $this->assertEquals($s['raw_metar'], $d->getRawMetar());
    }
    

}
