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
    'Entity/SurfaceWind.php',
    'Entity/RunwayVisualRange.php',
    'Entity/Visibility.php',
    'Entity/WeatherPhenomenon.php',
    'Entity/CloudLayer.php',
    'Entity/Value.php',

    'Exception/ChunkDecoderException.php',

    'ChunkDecoder/MetarChunkDecoder.php',
    'ChunkDecoder/MetarChunkDecoderInterface.php',
    'ChunkDecoder/ReportTypeChunkDecoder.php',
    'ChunkDecoder/IcaoChunkDecoder.php',
    'ChunkDecoder/DatetimeChunkDecoder.php',
    'ChunkDecoder/ReportStatusChunkDecoder.php',
    'ChunkDecoder/SurfaceWindChunkDecoder.php',
    'ChunkDecoder/VisibilityChunkDecoder.php',
    'ChunkDecoder/RunwayVisualRangeChunkDecoder.php',
    'ChunkDecoder/PresentWeatherChunkDecoder.php',
    'ChunkDecoder/CloudChunkDecoder.php',
    'ChunkDecoder/TemperatureChunkDecoder.php',
    'ChunkDecoder/PressureChunkDecoder.php',
    'ChunkDecoder/RecentWeatherChunkDecoder.php',
    'ChunkDecoder/WindShearChunkDecoder.php',
);

foreach ($to_include as $file) {
    require_once dirname(__FILE__).'/'.$file;
}
