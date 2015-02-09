<?php

namespace MetarDecoder\Test;

use MetarDecoder\MetarDecoder;
use DateTime;
use DateTimeZone;

class MetarDecoderTest extends \PHPUnit_Framework_TestCase
{
    private $decoder;

    public function __construct()
    {
        $this->decoder = new MetarDecoder();
    }

    public function testConstruct()
    {
        $d = new MetarDecoder();
    }

    /**
     * Test parsing of a complete, valid METAR
     */
    public function testParse()
    {
        // launch decoding
        $d = $this->decoder->parse('METAR  LFPO 231027Z    AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D FEW015 VV005 17/10 Q1009 ');

        // compare results
        $this->assertTrue($d->isValid());
        $this->assertEquals('METAR', $d->getType());
        $this->assertEquals('LFPO', $d->getIcao());
        $this->assertEquals('23', $d->getDay());
        $this->assertEquals(DateTime::createFromFormat('H:i', '10:27', new DateTimeZone('UTC')), $d->getTime());
        $this->assertEquals('AUTO', $d->getStatus());
        $w = $d->getSurfaceWind();
        $this->assertEquals('240', $w->getDirection());
        $this->assertEquals('04', $w->getSpeed());
        $this->assertEquals('09', $w->getSpeedVariations());
        $this->assertEquals('MPS', $w->getSpeedUnit());
        $v = $d->getVisibility();
        $this->assertEquals('2500', $v->getVisibility());
        $this->assertEquals('1000', $v->getMinimumVisibility());
        $this->assertEquals('NW', $v->getMinimumVisibilityDirection());
        $rs = $d->getRunwaysVisualRange();
        $r1 = $rs[0];
        $this->assertEquals('32', $r1->getRunway());
        $this->assertEquals('0400', $r1->getVisualRange());
        $this->assertEquals('', $r1->getPastTendency());
        $r2 = $rs[1];
        $this->assertEquals('08C', $r2->getRunway());
        $this->assertEquals('0004', $r2->getVisualRange());
        $this->assertEquals('D', $r2->getPastTendency());
        $cs = $d->getClouds();
        $c = $cs[0];
        $this->assertEquals('FEW', $c->getAmount());
        $this->assertEquals('015', $c->getBaseHeight());
        $this->assertEquals('005', $d->getVerticalVisibility());
        $this->assertEquals('17', $d->getAirTemperature());
        $this->assertEquals('10', $d->getDewPointTemperature());
        $this->assertEquals('1009', $d->getPressure());
    }

    /**
     * Test parsing of an empty METAR, which is valid
     */
    public function testParseNil()
    {
        $d = $this->decoder->parse('METAR LFPO 231027Z NIL');
        $this->assertEquals('NIL', $d->getStatus());
    }
    
    /**
     * Test parsing of a METAR with CAVOK
     */
    public function testParseCAVOK()
    {
        $d = $this->decoder->parse('METAR LFPO 231027Z AUTO 24004KT CAVOK 02/M08 Q0995');
        $this->assertTrue($d->getCavok());
        // TODO also check cloud and visibility information
        $this->assertEquals('0995', $d->getPressure());
    }
    
    /**
     * Test parsing of invalid METARs
     */
    public function testParseErrors()
    {
        $error_dataset = array(
            array('LFPG aaa bbb cccc', 'DatetimeChunkDecoder', 'AAA BBB CCCC '),
            array('METAR LFPO 231027Z NIL 1234', 'ReportStatusChunkDecoder', 'NIL 1234 '),
        );

        foreach ($error_dataset as $metar_error) {
            // launch decoding
            $d = $this->decoder->parse($metar_error[0]);

            // check the error triggered
            $this->assertFalse($d->isValid());
            $error = $d->getException();
            $this->assertEquals('MetarDecoder\ChunkDecoder\\'.$metar_error[1], $error->getChunkDecoder());
            $this->assertEquals($metar_error[2], $error->getChunk());
        }
    }
}
