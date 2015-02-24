<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\CloudLayer;
use MetarDecoder\Entity\Value;

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

    public function parse($remaining_metar, $cavok = false)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            // if cavok has been detected earlier in the metar, no problem
            if ($cavok) {
                $result = null;
            } else {
                throw new ChunkDecoderException($remaining_metar, 'Bad format for clouds information, applied regexp is "'.$this->getRegexp().'"', $this);
            }
        } else {
            $layers = null;
            $visibility = null;

            if ($found[2] != null) {
                // handle the case where no clouds observed
                // TODO what fields to map ?
            } else {
                // handle cloud layers and visibility
                $layers = array();
                for ($i = 3; $i <= 15; $i += 4) {
                    if ($found[$i] != null) {
                        $layer = new CloudLayer();
                        $layer_height = Value::toInt($found[$i+2]);
                        if ($layer_height != null) {
                            $layer_height_ft = $layer_height * 100;
                        } else {
                            $layer_height_ft = null;
                        }
                        $layer->setAmount($found[$i+1])
                              ->setBaseHeight(Value::newValue($layer_height_ft, Value::FEET))
                              ->setType($found[$i+3]);
                        $layers[] = $layer;
                    }
                }
                if ($found[19] != null) {
                    $visibility = Value::toInt($found[20]);
                }
            }
            $result = array(
                'clouds' => $layers,
                'verticalVisibility' => $visibility,
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
