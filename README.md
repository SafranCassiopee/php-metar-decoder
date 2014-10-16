PHP METAR decoder
=================

[![Build Status](https://travis-ci.org/inouire/php-metar-decoder.svg?branch=master)](https://travis-ci.org/inouire/php-metar-decoder)

PHP METAR decoder lib, under construction

by Edouard de Labareyre

Introduction
------------

This piece of software is a library package that provides a parser to decode raw METAR observation.

METAR is a format made for weather information reporting. METAR weather reports are predominantly used by pilots and by meteorologists, who use it to assist in weather forecasting.
Raw METAR format is highly standardized through the International Civil Aviation Organization (ICAO).

*	[METAR definition on wikipedia](http://en.wikipedia.org/wiki/METAR)
*	METAR format specification(link needed)

Requirement
-----------

This library package requires PHP 5.3 or later.

If you want to use it very easily, you should consider installing [composer](http://getcomposer.org) on your system.
It is not mandatory though.

Setup
-----

TODO

Usage
-----

TODO

Tests
-----

This library is fully unit tested, and uses [PHPUnit](https://phpunit.de/getting-started.html) to launch the tests.

Once you installed PHPUnit, launch the test suite with the following command:
    
    php phpunit.phar --bootstrap src/MetarDecoder/MetarDecoder.inc.php tests/MetarDecoder
