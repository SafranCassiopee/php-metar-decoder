<?php

namespace MetarDecoder;

use MetarDecoder\Entity\DecodedMetar;
use MetarDecoder\ChunkDecoder\ReportTypeChunkDecoder;
use MetarDecoder\ChunkDecoder\IcaoChunkDecoder;
use MetarDecoder\ChunkDecoder\DatetimeChunkDecoder;
use MetarDecoder\ChunkDecoder\ReportStatusChunkDecoder;
use MetarDecoder\ChunkDecoder\SurfaceWindChunkDecoder;
use MetarDecoder\ChunkDecoder\VisibilityChunkDecoder;
use MetarDecoder\ChunkDecoder\CloudChunkDecoder;
use MetarDecoder\ChunkDecoder\PressureChunkDecoder;
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
            //new RunwayVisualRange(),
            //TODO present weather
            //new CloudChunkDecoder(),
            //TODO air and dew point temperature
            //new PressureChunkDecoder(),
            //TODO recent weather
            //new WindShearChunkDecoder()
        );
    }

    /**
     * Decode a full metar string
     * Under construction
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
                $decoded = $chunk_decoder->parse($remaining_metar);
            } catch (ChunkDecoderException $cde) {
                // log error in decoded metar and abort decoding
                $decoded_metar->setException($cde);
                break;
            }

            // map obtained fields (if any) to the final decoded object
            $result = $decoded['result'];
            if ($result != null) {
                foreach ($result as $key => $value) {
                    $setter_name = 'set'.ucfirst($key);
                    $decoded_metar->$setter_name($value);
                }
            }

            // prepare new remaining metar for next round
            $remaining_metar = $decoded['remaining_metar'];

            // hook for report status decoder, abort if nil, but decoded metar is valid though
            if ($chunk_decoder instanceof ReportStatusChunkDecoder) {
                if ($decoded_metar->getStatus() == 'NIL') {
                    break;
                }
            }

            // hook for CAVOK decoder
            if ($chunk_decoder instanceof VisibilityChunkDecoder) {
                $with_cavok = $decoded_metar->getCavok();
            }
        }

        return $decoded_metar;
    }
}
