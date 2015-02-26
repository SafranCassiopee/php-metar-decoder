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
        $d = $this->decoder->parse('METAR  LFPO 231027Z   AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D FZRA +SN // VCBLSA FEW015 VV005 17/10 Q1009 RERASN WS R03');

        // compare results
        $this->assertTrue($d->isValid());
        $this->assertEquals('METAR', $d->getType());
        $this->assertEquals('LFPO', $d->getIcao());
        $this->assertEquals(23, $d->getDay());
        $this->assertEquals(DateTime::createFromFormat('H:i', '10:27', new DateTimeZone('UTC')), $d->getTime());
        $this->assertEquals('AUTO', $d->getStatus());
        $w = $d->getSurfaceWind();
        $this->assertEquals(240, $w->getDirection()->getValue());
        $this->assertEquals(4, $w->getSpeed()->getValue());
        $this->assertEquals(9, $w->getSpeedVariations()->getValue());
        $this->assertEquals('m/s', $w->getSpeed()->getUnit());
        $v = $d->getVisibility();
        $this->assertEquals(2500, $v->getVisibility()->getValue());
        $this->assertEquals(1000, $v->getMinimumVisibility()->getValue());
        $this->assertEquals('NW', $v->getMinimumVisibilityDirection());
        $rs = $d->getRunwaysVisualRange();
        $r1 = $rs[0];
        $this->assertEquals('32', $r1->getRunway());
        $this->assertEquals(400, $r1->getVisualRange()->getValue());
        $this->assertEquals('', $r1->getPastTendency());
        $r2 = $rs[1];
        $this->assertEquals('08C', $r2->getRunway());
        $this->assertEquals(4, $r2->getVisualRange()->getValue());
        $this->assertEquals('D', $r2->getPastTendency());
        $pw = $d->getPresentWeather();
        $this->assertEquals(array('FZRA', '+SN'), $pw->getPrecipitations());
        $this->assertEquals(array('//'), $pw->getObscurations());
        $this->assertEquals(array('BLSA'), $pw->getVicinities());
        $cs = $d->getClouds();
        $c = $cs[0];
        $this->assertEquals('FEW', $c->getAmount());
        $this->assertEquals(1500, $c->getBaseHeight()->getValue());
        $this->assertEquals(5, $d->getVerticalVisibility());
        $this->assertEquals(17, $d->getAirTemperature()->getValue());
        $this->assertEquals(10, $d->getDewPointTemperature()->getValue());
        $this->assertEquals(1009, $d->getPressure()->getValue());
        $this->assertEquals('hPA', $d->getPressure()->getUnit());
        $this->assertEquals('RASN', $d->getRecentWeather());
        $this->assertEquals('03', $d->getWindshearRunway());
    }

    /**
     * Test parsing of a short, valid METAR
     */
    public function testParseShort()
    {
        // launch decoding
        $d = $this->decoder->parse('METAR LFPB 190730Z AUTO 17005KT 6000 OVC024 02/00 Q1032 ');

        // compare results
        $this->assertTrue($d->isValid());
        $this->assertEquals('METAR', $d->getType());
        $this->assertEquals('LFPB', $d->getIcao());
        $this->assertEquals(19, $d->getDay());
        $this->assertEquals(DateTime::createFromFormat('H:i', '07:30', new DateTimeZone('UTC')), $d->getTime());
        $this->assertEquals('AUTO', $d->getStatus());
        $w = $d->getSurfaceWind();
        $this->assertEquals(170, $w->getDirection()->getValue());
        $this->assertEquals(5, $w->getSpeed()->getValue());
        $this->assertEquals('kt', $w->getSpeed()->getUnit());
        $v = $d->getVisibility();
        $this->assertEquals(6000, $v->getVisibility()->getValue());
        $cs = $d->getClouds();
        $c = $cs[0];
        $this->assertEquals('OVC', $c->getAmount());
        $this->assertEquals(2400, $c->getBaseHeight()->getValue());
        $this->assertEquals(2, $d->getAirTemperature()->getValue());
        $this->assertEquals(0, $d->getDewPointTemperature()->getValue());
        $this->assertEquals(1032, $d->getPressure()->getValue());
        $this->assertEquals('hPA', $d->getPressure()->getUnit());
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
        $this->assertEquals(995, $d->getPressure()->getValue());
    }

    /**
     * Test parsing of invalid METARs
     */
    public function testParseErrors()
    {
        $error_dataset = array(
            array('LFPG aaa bbb cccc', 'DatetimeChunkDecoder', 'AAA BBB CCCC '),
            array('METAR LFPO 231027Z NIL 1234', 'ReportStatusChunkDecoder', 'NIL 1234 '),
            array('METAR LFPO 231027Z AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D FZRAA FEW015 ','PresentWeatherChunkDecoder','FZRAA FEW015 '),
        );

        foreach ($error_dataset as $metar_error) {
            // launch decoding
            $d = $this->decoder->parse($metar_error[0]);

            // check the error triggered
            $this->assertFalse($d->isValid());
            $error = $d->getException();
            $this->assertEquals($metar_error[1], $error->getChunkDecoder());
            $this->assertEquals($metar_error[2], $error->getChunk());
        }
    }
}
