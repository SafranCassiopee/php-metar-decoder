<?php

namespace MetarDecoder\Test\Entity;

use MetarDecoder\Entity\DecodedMetar;
use \DateTime;
use \DateTimeZone;
use MetarDecoder\Exception\ChunkDecoderException;

class DecodedMetarTest extends \PHPUnit_Framework_TestCase
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
        
        // about fields content
        $this->assertEquals($s['icao'], $d->getIcao());
        $this->assertEquals($s['day'], $d->getDay());
        $this->assertEquals($s['time'], $d->getTime());
        $this->assertEquals($s['raw_metar'], $d->getRawMetar());
        
        // about metar validity
        $this->assertTrue($d->isValid());
        $error = new ChunkDecoderException('metar chunk','error message',$this);
        $d->setException($error);
        $this->assertFalse($d->isValid());
        $this->assertEquals($error, $d->getException());
        
    }
    

}
