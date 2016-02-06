<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\ReportStatusChunkDecoder;
use MetarDecoder\Exception\ChunkDecoderException;

class ChunkDecoderExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test handling of remaining metar
     */
    public function testFreshRemainingMetar()
    {
        $chunk_decoder = new ReportStatusChunkDecoder();

        // with regex match
        $exception  = new ChunkDecoderException("FAULTY_PART AAA BBB CC-D ", "BBB CC-D ", "This is an error message", $chunk_decoder);
        $this->assertEquals("BBB CC-D ", $exception->getFreshRemainingMetar());
        $this->assertEquals("ReportStatusChunkDecoder", $exception->getChunkDecoder());
        $this->assertEquals("FAULTY_PART AAA BBB CC-D", $exception->getChunk());

        // no regex match, consume one chunk blindly
        $exception  = new ChunkDecoderException("FAULTY_PART AAA BBB CC-D ", "FAULTY_PART AAA BBB CC-D ", "This is an error message", $chunk_decoder);
        $this->assertEquals("AAA BBB CC-D ", $exception->getFreshRemainingMetar());
    }

}
