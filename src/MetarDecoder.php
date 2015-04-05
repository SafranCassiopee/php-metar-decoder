<?php

namespace MetarDecoder;

use MetarDecoder\Entity\DecodedMetar;
use MetarDecoder\ChunkDecoder\ReportTypeChunkDecoder;
use MetarDecoder\ChunkDecoder\IcaoChunkDecoder;
use MetarDecoder\ChunkDecoder\DatetimeChunkDecoder;
use MetarDecoder\ChunkDecoder\ReportStatusChunkDecoder;
use MetarDecoder\ChunkDecoder\SurfaceWindChunkDecoder;
use MetarDecoder\ChunkDecoder\VisibilityChunkDecoder;
use MetarDecoder\ChunkDecoder\RunwayVisualRangeChunkDecoder;
use MetarDecoder\ChunkDecoder\PresentWeatherChunkDecoder;
use MetarDecoder\ChunkDecoder\CloudChunkDecoder;
use MetarDecoder\ChunkDecoder\TemperatureChunkDecoder;
use MetarDecoder\ChunkDecoder\PressureChunkDecoder;
use MetarDecoder\ChunkDecoder\RecentWeatherChunkDecoder;
use MetarDecoder\ChunkDecoder\WindShearChunkDecoder;
use MetarDecoder\Exception\ChunkDecoderException;

class MetarDecoder
{
    private $decoder_chain;

    public function __construct()
    {
        $this->decoder_chain = array(
            new ReportTypeChunkDecoder(),
            new IcaoChunkDecoder(),
            new DatetimeChunkDecoder(),
            new ReportStatusChunkDecoder(),
            new SurfaceWindChunkDecoder(),
            new VisibilityChunkDecoder(),
            new RunwayVisualRangeChunkDecoder(),
            new PresentWeatherChunkDecoder(),
            new CloudChunkDecoder(),
            new TemperatureChunkDecoder(),
            new PressureChunkDecoder(),
            new RecentWeatherChunkDecoder(),
            new WindShearChunkDecoder(),
        );
    }

    /**
     * Decode a full metar string into a complete metar object
     */
    public function parse($raw_metar)
    {
        // prepare decoding inputs/outputs (upper case + trim + no more than one space)
        $clean_metar = preg_replace("#[ ]{2,}#", ' ', trim(strtoupper($raw_metar))).' ';
        $remaining_metar = $clean_metar;
        $decoded_metar = new DecodedMetar($clean_metar);
        $with_cavok = false;

        // call each decoder in the chain and use results to populate decoded metar
        foreach ($this->decoder_chain as $chunk_decoder) {
            // try to parse a chunk with current chunk decoder
            try {
                $decoded = $chunk_decoder->parse($remaining_metar, $with_cavok);
            } catch (ChunkDecoderException $cde) {
                // log error in decoded metar and abort decoding
                $decoded_metar->setDecodingException($cde);
                break;
            }

            // map obtained fields (if any) to the final decoded object
            $result = $decoded['result'];
            if ($result != null) {
                foreach ($result as $key => $value) {
                    if ($value !== null) {
                        $setter_name = 'set'.ucfirst($key);
                        $decoded_metar->$setter_name($value);
                    }
                }
            }

            // update remaining metar for next round
            $remaining_metar = $decoded['remaining_metar'];

            // hook for report status decoder, abort if nil, but decoded metar is valid though
            if ($chunk_decoder instanceof ReportStatusChunkDecoder) {
                if ($decoded_metar->getStatus() == 'NIL') {
                    break;
                }
            }

            // hook for CAVOK decoder, keep CAVOK information in memory
            if ($chunk_decoder instanceof VisibilityChunkDecoder) {
                $with_cavok = $decoded_metar->getCavok();
            }
        }

        return $decoded_metar;
    }
}
