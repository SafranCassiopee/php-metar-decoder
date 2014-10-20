<?php

# Use this file if you cannot use class autoloading. It will include all
# the files needed for the Metar decoder.
#
# Use composer to install this library if you want a simple autoloader setup.
# To know how to use composer, see README.md

require_once dirname(__FILE__) . '/MetarDecoder.php';
require_once dirname(__FILE__) . '/Entity/DecodedMetar.php';

require_once dirname(__FILE__) . '/Service/MetarChunkDecoder.php';
require_once dirname(__FILE__) . '/Service/MetarChunkDecoderInterface.php';

require_once dirname(__FILE__) . '/Service/IcaoChunkDecoder.php';
require_once dirname(__FILE__) . '/Service/DatetimeChunkDecoder.php';
