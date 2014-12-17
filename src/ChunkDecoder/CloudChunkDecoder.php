<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\CloudLayer;

/**
 * Chunk decoder for cloud section
 */
class CloudChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $no_cloud = "(NSC|NCD|CLR|SKC)";
        $layer = "(FEW|SCT|BKN|OVC|///)([0-9]{3}|///)(CB|TCU|///)?";
        $vertical_visibility = "VV([0-9]{3}|///)";
        
        return "#^($no_cloud|($layer)( $layer)?( $layer)?( $layer)?( $vertical_visibility)?)( )#";
    }

    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        //var_dump($found);
        // handle the case where nothing has been found
        if ($found == null) {
            // TODO no error in the case of CAVOK
            throw new ChunkDecoderException($remaining_metar, 'Bad format for clouds information, applied regexp is "'.$this->getRegexp().'"', $this);
        }

        $layers = null;
        $visibility = null;
            
        if($found[2] != null){
            // handle the case where no clouds observed
            // TODO what fields ?
        }else{
            // handle cloud layers and visibility
            $layers = array();
            for($i = 3; $i <= 15 ; $i+= 4 ){
                if($found[$i] != null){
                    $layer = new CloudLayer();
                    $layer->setAmount($found[$i+1])
                          ->setBaseHeight($found[$i+2])
                          ->setType($found[$i+3]);
                    $layers[] = $layer;
                }
            }
            if($found[19] != null){
                $visibility = $found[20];
            }
        }
        
        // return result + remaining metar
        return array(
            'result' => array(
                'clouds' => $layers,
                'verticalVisibility' => $visibility,
            ),
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
