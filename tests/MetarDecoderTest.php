<?php

namespace MetarDecoder\Test;

use MetarDecoder\MetarDecoder;

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
        $raw_metar = 'METAR  LFPO 231027Z   AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D +FZRA VCSN // FEW015 VV005 17/10 Q1009 REFZRA WS R03';
        $d = $this->decoder->parse($raw_metar);

        // compare results
        $this->assertTrue($d->isValid());
        $this->assertEquals('METAR LFPO 231027Z AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D +FZRA VCSN // FEW015 VV005 17/10 Q1009 REFZRA WS R03', $d->getRawMetar());
        $this->assertEquals('METAR', $d->getType());
        $this->assertEquals('LFPO', $d->getIcao());
        $this->assertEquals(23, $d->getDay());
        $this->assertEquals('10:27 UTC', $d->getTime());
        $this->assertEquals('AUTO', $d->getStatus());
        $w = $d->getSurfaceWind();
        $this->assertEquals(240, $w->getMeanDirection()->getValue());
        $this->assertEquals(4, $w->getMeanSpeed()->getValue());
        $this->assertEquals(9, $w->getSpeedVariations()->getValue());
        $this->assertEquals('m/s', $w->getMeanSpeed()->getUnit());
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
        $this->assertEquals(3, count($pw));
        $pw1 = $pw[0];
        $this->assertEquals('+', $pw1->getIntensity());
        $this->assertEquals('FZ', $pw1->getCaracterisation());
        $this->assertEquals(array('RA'), $pw1->getTypes());
        $pw2 = $pw[1];
        $this->assertEquals('VC', $pw2->getIntensity());
        $this->assertEquals(null, $pw2->getCaracterisation());
        $this->assertEquals(array('SN'), $pw2->getTypes());
        $pw3 = $pw[2];
        $this->assertEquals(null, $pw3->getIntensity());
        $this->assertEquals(null, $pw3->getCaracterisation());
        $this->assertEquals(array('//'), $pw3->getTypes());
        $cs = $d->getClouds();
        $c = $cs[0];
        $this->assertEquals('FEW', $c->getAmount());
        $this->assertEquals(1500, $c->getBaseHeight()->getValue());
        $this->assertEquals(500, $d->getVerticalVisibility()->getValue());
        $this->assertEquals(17, $d->getAirTemperature()->getValue());
        $this->assertEquals(10, $d->getDewPointTemperature()->getValue());
        $this->assertEquals(1009, $d->getPressure()->getValue());
        $this->assertEquals('hPa', $d->getPressure()->getUnit());
        $rw = $d->getRecentWeather();
        $this->assertEquals('FZ', $rw->getCaracterisation());
        $this->assertEquals('RA', current($rw->getTypes()));
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
        $this->assertEquals('07:30 UTC', $d->getTime());
        $this->assertEquals('AUTO', $d->getStatus());
        $w = $d->getSurfaceWind();
        $this->assertEquals(170, $w->getMeanDirection()->getValue());
        $this->assertEquals(5, $w->getmeanSpeed()->getValue());
        $this->assertEquals('kt', $w->getMeanSpeed()->getUnit());
        $v = $d->getVisibility();
        $this->assertEquals(6000, $v->getVisibility()->getValue());
        $cs = $d->getClouds();
        $c = $cs[0];
        $this->assertEquals('OVC', $c->getAmount());
        $this->assertEquals(2400, $c->getBaseHeight()->getValue());
        $this->assertEquals(2, $d->getAirTemperature()->getValue());
        $this->assertEquals(0, $d->getDewPointTemperature()->getValue());
        $this->assertEquals(1032, $d->getPressure()->getValue());
        $this->assertEquals('hPa', $d->getPressure()->getUnit());
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
        $this->assertNull($d->getVisibility());
        $this->assertNull($d->getClouds());
        $this->assertNull($d->getVerticalVisibility());
        // check that we went to the end of the decoding though
        $this->assertEquals(995, $d->getPressure()->getValue());
    }

    /**
     * Test parsing of invalid METARs
     */
    public function testParseErrors()
    {
        $error_dataset = array(
            array('LFPG aaa bbb cccc', 'DatetimeChunkDecoder', 'AAA BBB CCCC'),
            array('METAR LFPO 231027Z NIL 1234', 'ReportStatusChunkDecoder', 'NIL 1234'),
            array('METAR LFPO 231027Z AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D FZRAA FEW015 ','CloudChunkDecoder','FZRAA FEW015'),
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
