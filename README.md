PHP METAR decoder
=================

[![License](https://poser.pugx.org/sagem-cassiopee/php-metar-decoder/license.svg)](https://packagist.org/packages/sagem-cassiopee/php-metar-decoder)
[![Build Status](https://travis-ci.org/SagemCassiopee/php-metar-decoder.svg)](https://travis-ci.org/SagemCassiopee/php-metar-decoder)
[![Coverage Status](https://coveralls.io/repos/SagemCassiopee/php-metar-decoder/badge.svg?branch=master&service=github)](https://coveralls.io/github/SagemCassiopee/php-metar-decoder?branch=master)
[![Latest Stable Version](https://poser.pugx.org/sagem-cassiopee/php-metar-decoder/v/stable.svg)](https://packagist.org/packages/sagem-cassiopee/php-metar-decoder)

A PHP library to decode METAR strings, fully unit tested (100% code coverage)

Try it on the [demo website](https://php-metar-decoder.cassiopee.aero)

Introduction
------------

This piece of software is a library package that provides a parser to decode raw METAR observation.

METAR is a format made for weather information reporting. METAR weather reports are predominantly used by pilots and by meteorologists, who use it to assist in weather forecasting.
Raw METAR format is highly standardized through the International Civil Aviation Organization (ICAO).

*    [METAR definition on wikipedia](http://en.wikipedia.org/wiki/METAR)
*    [METAR format specification](http://www.wmo.int/pages/prog/www/WMOCodes/WMO306_vI1/VolumeI.1.html)
*    [METAR documentation](http://meteocentre.com/doc/metar.html)

Requirements
------------

This library package only requires PHP >= 5.3 

It is currently tested automatically for PHP 5.3, 5.4 and 5.5.

If you want to integrate it easily in your project, you should consider installing [composer](http://getcomposer.org) on your system.
It is not mandatory though.

Setup
-----

- With composer *(recommended)*

Add the following line to the `composer.json` of your project

```json
{
    "require": {
        "sagem-cassiopee/php-metar-decoder": "dev-master"
    }
}
```

Launch install from your project root with:

```shell
composer install --no-dev
```

Load the library thanks to composer autoloading:

```php
<?php
require_once 'vendor/autoload.php';
```

- By hand

Download the latest release from [github](https://github.com/SagemCassiopee/php-metar-decoder/releases)

Extract it wherever you want in your project. The library itself is in the src/ directory, the other directories are not mandatory for the library to work.

Load the library with the static import file:

```php
<?php
require_once 'path/to/MetarDecoder/MetarDecoder.inc.php';
```

Usage
-----

Instantiate the decoder and launch it on a METAR string.
The returned object is a DecodedMetar object from which you can retrieve all the weather properties that have been decoded.

All values who have a unit are based on the `Value` object which provides the methods `getValue()` and `getUnit()`

*TODO: full documentation of the structure of the DecodedMetar object*

```php
<?php

require_once 'vendor/autoload.php';

$decoder = new MetarDecoder\MetarDecoder();
$d = $decoder->parse('METAR LFPO 231027Z AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D +FZRA VCSN //FEW015 17/10 Q1009 REFZRA WS R03')

//context information
$d->isValid()); //true
$d->getRawMetar(); //'METAR LFPO 231027Z AUTO 24004G09MPS 2500 1000NW R32/0400 R08C/0004D +FZRA VCSN //FEW015 17/10 Q1009 REFZRA WS R03'
$d->getType(); //'METAR'
$d->getIcao(); //'LFPO'
$d->getDay(); //23
$d->getTime(); //'10:27 UTC'
$d->getStatus(); //'AUTO'

//surface wind
$sw = $d->getSurfaceWind(); //SurfaceWind object
$sw->getMeanDirection()->getValue(); //240
$sw->getMeanSpeed()->getValue(); //4
$sw->getSpeedVariations()->getValue(); //9
$sw->getMeanSpeed()->getUnit(); //'m/s'

//visibility
$v = $d->getVisibility(); //Visibility object
$v->getVisibility()->getValue(); //2500
$v->getVisibility()->getUnit(); //'m'
$v->getMinimumVisibility()->getValue(); //1000
$v->getMinimumVisibilityDirection(); //'NW'
$v->hasNDV(); //false

//runway visual range
$rvr = $d->getRunwaysVisualRange(); //RunwayVisualRange array
$rvr[0]->getRunway(); //'32'
$rvr[0]->getVisualRange()->getValue(); //400
$rvr[0]->getPastTendency(); //''
$rvr[1]->getRunway(); //'08C'
$rvr[1]->getVisualRange()->getValue(); //4
$rvr[1]->getPastTendency(); //'D'

//present weather
$pw = $d->getPresentWeather(); //WeatherPhenomenon array
$pw[0]->getIntensityProximity(); //'+'
$pw[0]->getCharacteristics(); //'FZ'
$pw[0]->getTypes(); //array('RA')
$pw[1]->getIntensityProximity(); //'VC'
$pw[1]->getCharacteristics(); //null
$pw[1]->getTypes(); //array('SN')

// clouds
$cld = $d->getClouds(); //CloudLayer array
$cld[0]->getAmount(); //'FEW'
$cld[0]->getBaseHeight()->getValue(); //1500
$cld[0]->getBaseHeight()->getUnit(); //'ft'

// temperature
$d->getAirTemperature()->getValue(); //17
$d->getAirTemperature()->getUnit(); //'deg C'
$d->getDewPointTemperature()->getValue(); //10

// pressure
$d->getPressure()->getValue(); //1009
$d->getPressure()->getUnit(); //'hPa'

// recent weather
$rw = $d->getRecentWeather();
$rw->getCharacteristics(); //'FZ'
$rw->getTypes(); //array('RA')

// windshears
$d->getWindshearRunways(); //array('03')

```

About Value objects
-------------------

In the example above, it is assumed that all requested parameters are available. 
In the real world, some fields are not mandatory thus it is important to check that the Value object (containing both the value and its unit) is not null before using it.
What you do in case it's null is totally up to you.

Here is an example:

```php
// check that the $dew_point is not null and give it a default value if it is
$dew_point = $d->getDewPointTemperature();
if($dew_point == null){
    $dew_point = new Value(999, Value::DEGREE_CELSIUS)
}

// $dew_point object can now be accessed safely
$dew_point->getValue();
$dew_point->getUnit();
```

Value objects also contain their unit, that you can access with the `getUnit()` method. When you call `getValue()`, you'll get the value in this unit. 

If you want to get the value directly in another unit you can call `getConvertedValue($unit)`. Supported values are speed, distance and pressure. 

Here are all available units for conversion:

```php
// speed units:
Value::METER_PER_SECOND
Value::KILOMETER_PER_HOUR
Value::KNOT

// distance units:
Value::METER
Value::FEET
Value::STATUTE_MILE

// pressure units:
Value::HECTO_PASCAL
Value::MERCURY_INCH

// use on-the-fly conversion
$distance_in_sm = $visibility->getConvertedValue(Value::STATUTE_MILE);
$speed_kph = $speed->getConvertedValue(Value::KILOMETER_PER_HOUR);
```

About parsing errors
--------------------

When an unexpected format is encountered for a part of the METAR, the parsing error is logged into the DecodedMetar object itself.

All parsing errors for one metar can be accessed through the `getDecodingExceptions()` method.

By default parsing will continue when a bad format is encountered.
But the parser also provides a "strict" mode where parsing stops as soon as an error occurs.
The mode can be set globally for a MetarDecoder object, or just once as you can see in this example:

```php
<?php

$decoder = new MetarDecoder\MetarDecoder();

// change global parsing mode to "strict"
$decoder->setStrictParsing(true);

// this parsing will be made with strict mode
$decoder->parse("...");

// but this one will ignore global mode and will be made with not-strict mode anyway
$decoder->parseNotStrict("...");

// change global parsing mode to "not-strict"
$decoder->setStrictParsing(false);

// this parsing will be made with no-strict mode
$decoder->parse("...");

// but this one will ignore global mode and will be made with strict mode anyway
$decoder->parseStrict("...");

```

About parsing errors, again
---------------------------

In non-strict mode, it is possible to get a parsing error for a given chunk decoder, while still getting the decoded information for this chunk in the end. How is that possible ?

It is because non-strict mode not only continues decoding where there is an error, it also tries the parsing again on the "next chunk" (based on whitespace separator). But all errors on first try will remain logged even if the second try suceeded.

Let's say you have this chunk `AAA 12003KPH ...` provided to the SurfaceWind chunk decoder. This decoder will choke on `AAA`, will try to decode `12003KPH` and will succeed. The first exception for surface wind decoder will be kept but the SurfaceWind object will be filled with some information.

All of this does not apply to strict mode as parsing is interrupted on first parsing error in this case.

Contribute
----------

If you find a valid METAR that is badly parsed by this library, please open a github issue with all possible details:

- the full METAR causing problem
- the parsing exception returned by the library
- how you expected the decoder to behave
- anything to support your proposal (links to official websites appreciated)

If you want to improve or enrich the test suite, fork the repository and submit your changes with a pull request.

If you have any other idea to improve the library, please use github issues or directly pull requests depending on what you're more comfortable with.

Tests and coverage
------------------

This library is fully unit tested, and uses [PHPUnit](https://phpunit.de/getting-started.html) to launch the tests.

Travis CI is used for continuous integration, which triggers tests for PHP 5.3, 5.4, 5.5 for each push to the repo.

To run the tests by yourself, you must first install the dev dependencies ([composer](http://getcomposer.org) needed)

```shell
composer install --dev
apt-get install php5-xdebug # only needed if you're interested in code coverage
```

Launch the test suite with the following command:
    
```shell
./vendor/bin/phpunit tests
```

You can also generate an html coverage report by adding the `--coverage-html` option:

```shell
./vendor/bin/phpunit --coverage-html ./report tests
```



