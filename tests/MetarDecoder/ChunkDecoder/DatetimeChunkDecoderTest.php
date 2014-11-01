<?php

use MetarDecoder\ChunkDecoder\DatetimeChunkDecoder;

use \DateTime;
use \DateTimeZone;
use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Service\DatasetProvider;

class DatetimeChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    public function testParse()
    {        
        $chunk_decoder = new DatetimeChunkDecoder();
        $dsp = new DatasetProvider('./test-data/chunk');
        
        foreach($dsp->getDataset('date_time_chunk_decoding.csv') as $data){
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
                $expected_time = DateTime::createFromFormat('H:i',$data['expected']['time'],new DateTimeZone('UTC'));
                $this->assertEquals($data['expected']['day'],  $decoded['result']['day']);
                $this->assertEquals($data['expected']['remaining'], $decoded['remaining_metar']);
                $this->assertEquals($expected_time, $decoded['result']['time']);
            }
        }
    }
  

}
