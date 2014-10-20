PHP METAR decoder
=================

[![Build Status](https://travis-ci.org/inouire/php-metar-decoder.svg?branch=master)](https://travis-ci.org/inouire/php-metar-decoder)

PHP METAR decoder lib, under construction

Introduction
------------

This piece of software is a library package that provides a parser to decode raw METAR observation.

METAR is a format made for weather information reporting. METAR weather reports are predominantly used by pilots and by meteorologists, who use it to assist in weather forecasting.
Raw METAR format is highly standardized through the International Civil Aviation Organization (ICAO).

*    [METAR definition on wikipedia](http://en.wikipedia.org/wiki/METAR)
*    METAR format specification(link needed)
*    [METAR documentation](http://meteocentre.com/doc/metar.html)

Requirement
-----------

This library package requires PHP 5.3 or later.

If you want to use it very easily, you should consider installing [composer](http://getcomposer.org) on your system.
It is not mandatory though.

Setup
-----

- With composer (recommanded)

TODO

- By hand

TODO

Usage
-----

First load the library thanks to autoloading

```php
<?php
require_once 'vendor/autoload.php';
```

Or with this if you use the library manually

```php
<?php
require_once 'path/to/MetarDecoder/MetarDecoder.inc.php';
```


Instanciate the decoder and launch it on a METAR string.
The returned object is a DecodedMetar object from which you can retrieve all the weather propertie that have been decoded.

```php
<?php

require_once 'vendor/autoload.php';

$decoder = new MetarDecoder\MetarDecoder();
$result = $decoder->parse('PAPO 131156Z 31014KT 5SM +DZ BR OVC042 M23/M27 A2959 RMK A01 11200 21230 52010')

$result->getIcao();
$result->getDatetime();
// to be completed

```

Contribute
----------

Install dev dependencies ([composer](http://getcomposer.org) needed)

    composer install --dev
    
Install xdebug (needed only for code coverage)

    apt-get install php5-xdebug

Tests and coverage
------------------

This library is fully unit tested, and uses [PHPUnit](https://phpunit.de/getting-started.html) to launch the tests.

Once you installed the dev dependencies, launch the test suite with the following command:
    
    ./vendor/bin/phpunit --bootstrap src/MetarDecoder/MetarDecoder.inc.php tests/MetarDecoder

You can also generate an html coverage report by adding the `--coverage-html` option:

    ./vendor/bin/phpunit --bootstrap src/MetarDecoder/MetarDecoder.inc.php --coverage-html ./report tests/MetarDecoder 
