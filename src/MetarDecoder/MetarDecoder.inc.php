<?php

/**
 * Use this file if you cannot use class autoloading. It will include all
 * the files needed for the Metar decoder.
 *
 * Use composer to install this library if you want a simple autoloader setup.
 * To know how to use composer, see README.md
 */

$to_include = array(
    'MetarDecoder.php',
    'Entity/DecodedMetar.php',
    
    'Service/MetarChunkDecoder.php',
    'Service/MetarChunkDecoderInterface.php',
    
    'Service/ReportTypeChunkDecoder.php',
    'Service/IcaoChunkDecoder.php',
    'Service/DatetimeChunkDecoder.php'
);

foreach($to_include as $file){
    require_once dirname(__FILE__) . '/'.$file;
}

