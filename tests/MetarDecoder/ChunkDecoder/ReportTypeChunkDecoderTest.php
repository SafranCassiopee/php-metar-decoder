<?php

use MetarDecoder\ChunkDecoder\ReportTypeChunkDecoder;
use MetarDecoder\Service\DatasetProvider;

class ReportTypeChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    public function testParse()
    {        
        $chunk_decoder = new ReportTypeChunkDecoder();
        $dsp = new DatasetProvider('./test-data/chunk');
        
        foreach($dsp->getDataset('report_type_chunk_decoding.csv') as $data){
            $decoded = $chunk_decoder->parse($data['input']['chunk']);
            $this->assertEquals($data['expected']['type'], $decoded['result']['type']);
            $this->assertEquals($data['expected']['remaining'], $decoded['remaining_metar']);
        }
    }
    

}
