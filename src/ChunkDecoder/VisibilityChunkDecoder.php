<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;
use MetarDecoder\Entity\Visibility;

/**
 * Chunk decoder for visibility section
 */
class VisibilityChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        $cavok = "CAVOK";
        $visibility = "([0-9]{4})";
        $minimum_visibility = "( ([0-9]{4})(N|NE|E|SE|S|SW|W|NW)?)?";// optionnal
        //$sm_visibility = "([0-9] )?((([0-9]{1,4}|-|([0-9]/[0-9]))(SM) ){1,})";

        return "#^($cavok|$visibility$minimum_visibility)( )#";
    }

    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);

        // handle the case where nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar, 'Bad format for visibility information, applied regexp is "'.$this->getRegexp().'"', $this);
        }

        if ($found[1] ==  'CAVOK') {
            // handle the CAVOK case
            $cavok = true;
            $visibility = null;
        } else {
            // retrieve found params
            $cavok = false;
            $visibility = new Visibility();
            $visibility->setVisibility($found[2])
                       ->setMinimumVisibility($found[4])
                       ->setMinimumVisibilityDirection($found[5]);
        }

        // return result + remaining metar
        return array(
            'result' => array(
                'cavok' => $cavok,
                'visibility' => $visibility,
            ),
            'remaining_metar' => $this->getRemainingMetar($remaining_metar),
        );
    }
}
