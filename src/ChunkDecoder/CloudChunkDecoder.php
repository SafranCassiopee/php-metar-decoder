<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\CloudLayer;
use MetarDecoder\Entity\Value;

/**
 * Chunk decoder for cloud section.
 */
class CloudChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $no_cloud = '(NSC|NCD|CLR|SKC)';
        $layer = '(VV|FEW|SCT|BKN|OVC|///)([0-9]{3}|///)(CB|TCU|///)?';
        // vertical visibility VV is handled as a regular cloud layer
        return "#^($no_cloud|($layer)( $layer)?( $layer)?( $layer)?( $layer)?( $layer)?)( )#";
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $result = $this->consume($remaining_metar);
        $found = $result['found'];
        $new_remaining_metar = $result['remaining'];

        // handle the case where nothing has been found and metar is not cavok
        if ($found == null && !$cavok) {
            throw new ChunkDecoderException($remaining_metar,
                                            $new_remaining_metar,
                                            'Bad format for clouds information',
                                            $this);
        }

        // default case: CAVOK or clear sky, no cloud layer
        $result = array(
            'clouds' => array(),
        );

        // there are clouds, handle cloud layers and visibility
        if ($found != null && $found[2] == null && $found[0] != "//////") {
            for ($i = 3; $i <= 23; $i += 4) {
                if ($found[$i] != null) {
                    $layer = new CloudLayer();
                    $layer_height = Value::toInt($found[$i + 2]);
                    if ($layer_height !== null) {
                        $layer_height_ft = $layer_height * 100;
                    } else {
                        $layer_height_ft = null;
                    }
                    $layer->setAmount($found[$i + 1])
                          ->setBaseHeight(Value::newValue($layer_height_ft, Value::FEET))
                          ->setType($found[$i + 3]);
                    $result['clouds'][] = $layer;
                }
            }
        } else {
            if($found != null && $found[2] == null && $found[0] == "//////") {
            $layer_null = new CloudLayer();
            $layer_null->setAmount($found[5])
                       
            $result['clouds'][] = $layer_null; //just as a tag means no cloud data, also could be change to any else string, likes "no data" etc.
            }
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $new_remaining_metar,
        );
    }
}
