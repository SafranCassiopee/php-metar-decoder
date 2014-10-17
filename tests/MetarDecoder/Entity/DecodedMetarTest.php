<?php

use MetarDecoder\Entity\DecodedMetar;

class DecodedMetarTest extends PHPUnit_Framework_TestCase
{

    
    private $dataset = array(
        'raw_metar' => 'ABCD EFGH',
        'icao' => 'POLI',
        'datetime' => '2014-10-17T16:27:00Z'
    );
    
    public function __construct()
    {

    }
    
    public function testGetSet()
    {
        $s = $this->dataset;
        $d = new DecodedMetar($s['raw_metar']);
        $d->setIcao($s['icao'])
          ->setDatetime($s['datetime']);
                     
        $this->assertEquals($s['icao'], $d->getIcao());
        $this->assertEquals($s['datetime'], $d->getDatetime());
        $this->assertEquals($s['raw_metar'], $d->getRawMetar());
    }
    

}
