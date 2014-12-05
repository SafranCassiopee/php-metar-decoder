<?php

namespace MetarDecoder\Test\ChunkDecoder;

use MetarDecoder\ChunkDecoder\ReportStatusChunkDecoder;
use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Service\DatasetProvider;

class ReportStatusChunkDecoderTest extends \PHPUnit_Framework_TestCase
{

    public function testParse()
    {        
        $chunk_decoder = new ReportStatusChunkDecoder();
        $dsp = new DatasetProvider('./test-data/chunk');
        
        foreach($dsp->getDataset('report_status_chunk_decoding.csv') as $data){
            if($data['expected']['exception']){
                // case when exceptions are expected
                try{
                    $input = $data['input']['chunk'];
                    $decoded = $chunk_decoder->parse($input);
                    $this->fail('Parsing "'.$input.'" should have raised an exception');
                }catch(ChunkDecoderException $cde){}
            }else{
                // case when valid data is expected
                $decoded = $chunk_decoder->parse($data['input']['chunk']);
                $this->assertEquals($data['expected']['status'], $decoded['result']['status']);
                $this->assertEquals($data['expected']['remaining'], $decoded['remaining_metar']);
            }
        }
    }

}
