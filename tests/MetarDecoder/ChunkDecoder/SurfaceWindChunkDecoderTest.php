<?php

use MetarDecoder\ChunkDecoder\SurfaceWindChunkDecoder;
use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Service\DatasetProvider;

class SurfaceWindChunkDecoderTest extends PHPUnit_Framework_TestCase
{

    public function testParse()
    {        
        $chunk_decoder = new SurfaceWindChunkDecoder();
        $dsp = new DatasetProvider('./test-data/chunk');
        
        foreach($dsp->getDataset('surface_wind_chunk_decoding.csv') as $data){
            if($data['expected']['exception']){
                // case when exceptions are expected
                try{
                    $input = $data['input']['chunk'];
                    $decoded = $chunk_decoder->parse($input);
                    $this->fail('Parsing "'.$input.'" should have raised an exception');
                }catch(ChunkDecoderException $cde){}
            }else{
                // case when valid data is expected, compare different field from wind object
                $decoded = $chunk_decoder->parse($data['input']['chunk']);
                $wind = $decoded['result']['surfaceWind'];
                foreach($data['expected'] as $key => $value){
                    if($key != 'exception' && $key != 'remaining' ){
                        $getter_name = 'get'.ucfirst($key);
                        $this->assertEquals($value, $wind->$getter_name());
                    }
                }
                $this->assertEquals($data['expected']['remaining'], $decoded['remaining_metar']);
            }
        }
    }

}
