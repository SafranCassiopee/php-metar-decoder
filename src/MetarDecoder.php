<?php

namespace MetarDecoder;

use MetarDecoder\Entity\DecodedMetar;
use MetarDecoder\ChunkDecoder\MetarChunkDecoder;
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

    private $strict_parsing = false;

    protected $global_strict_parsing = false;

    protected $discard_faulty_part_on_error = true;

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
     * Set global parsing mode (strict/not strict) for the whole object.
     */
    public function setStrictParsing($is_strict)
    {
        $this->global_strict_parsing = $is_strict;
    }

    /**
     * Decode a full metar string into a complete metar object
     * while using global strict option.
     */
    public function parse($raw_metar)
    {
        return $this->parseWithMode($raw_metar, $this->global_strict_parsing);
    }

    /**
     * Decode a full metar string into a complete metar object
     * with strict option, meaning decoding will stop as soon as
     * a non-compliance is detected.
     */
    public function parseStrict($raw_metar)
    {
        return $this->parseWithMode($raw_metar, true);
    }

    /**
     * Decode a full metar string into a complete metar object
     * with strict option disabled, meaning that decoding will
     * continue even if metar is not compliant.
     */
    public function parseNotStrict($raw_metar)
    {
        return $this->parseWithMode($raw_metar, false);
    }

    public function tryParsing($chunk_decoder, $strict, $remaining_metar, $with_cavok)
    {
        try {
            $decoded = $chunk_decoder->parse($remaining_metar, $with_cavok);
        } catch (ChunkDecoderException $primary_exception) {
            if ($strict) {
                throw $primary_exception;
            } else {
                try {
                    $alternative_remaining_metar = MetarChunkDecoder::consumeOneChunk($remaining_metar, $strict);
                    $decoded = $chunk_decoder->parse($alternative_remaining_metar, $with_cavok);
                    $decoded['exception'] = $primary_exception;
                } catch (ChunkDecoderException $secondary_exception) {
                    throw $primary_exception;
                }
            }
        }

        return $decoded;
    }

    /**
     * Decode a full metar string into a complete metar object.
     */
    private function parseWithMode($raw_metar, $strict)
    {
        // prepare decoding inputs/outputs: (upper case, trim,
        // remove 'end of message', no more than one space)
        $clean_metar = trim(strtoupper($raw_metar));
        $clean_metar = preg_replace('#=$#', '', $clean_metar);
        $clean_metar = preg_replace('#[ ]{2,}#', ' ', $clean_metar).' ';
        $remaining_metar = $clean_metar;
        $decoded_metar = new DecodedMetar($clean_metar);
        $with_cavok = false;

        // call each decoder in the chain and use results to populate decoded metar
        foreach ($this->decoder_chain as $chunk_decoder) {
            try {
                // try to parse a chunk with current chunk decoder
                $decoded = $this->tryParsing($chunk_decoder, $strict, $remaining_metar, $with_cavok);

                // log any excception that would have occur at primary decoding
                if (array_key_exists('exception', $decoded)) {
                    $decoded_metar->addDecodingException($decoded['exception']);
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
            } catch (ChunkDecoderException $cde) {
                // log error in decoded metar
                $decoded_metar->addDecodingException($cde);

                // abort decoding if strict mode is activated, continue otherwise
                if ($strict) {
                    break;
                }

                // update remaining metar for next round
                $remaining_metar = $cde->getRemainingMetar();
            }

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
