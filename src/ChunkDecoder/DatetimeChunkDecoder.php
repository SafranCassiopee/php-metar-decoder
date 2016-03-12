<?php

namespace MetarDecoder\ChunkDecoder;

use MetarDecoder\Exception\ChunkDecoderException;

/**
 * Chunk decoder for date+time section.
 */
class DatetimeChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    public function getRegexp()
    {
        return '#^([0-9]{2})([0-9]{2})([0-9]{2})Z #';
    }

    public function parse($remaining_metar, $cavok = false)
    {
        $result = $this->consume($remaining_metar);
        $found = $result['found'];
        $new_remaining_metar = $result['remaining'];

        // handle the case where nothing has been found
        if ($found == null) {
            throw new ChunkDecoderException($remaining_metar,
                                            $new_remaining_metar,
                                            'Missing or badly formatted day/hour/minute information ("ddhhmmZ" expected)',
                                            $this);
        } else {
            // retrieve found params and check them
            $day = intval($found[1]);
            $hour = $found[2];
            $minute = $found[3];
            if (!$this->checkValidity($day, $hour, $minute)) {
                throw new ChunkDecoderException($remaining_metar,
                                                $new_remaining_metar,
                                                'Invalid values for day/hour/minute',
                                                $this);
            }
            $result = array(
                'day' => $day,
                'time' => $found[2].':'.$found[3].' UTC',
            );
        }

        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $new_remaining_metar,
        );
    }

    /**
     * Check the validity of the decoded information for date time.
     *
     * @param string $day
     * @param string $hour
     * @param string $minute
     *
     * @return bool true if valid, false if not
     */
    private function checkValidity($day, $hour, $minute)
    {
        // convert as integers
        $day_int = intval($day);
        $hour_int = intval($hour);
        $minute_int = intval($minute);

        // check value range
        if ($day_int  < 1 || $day_int > 31) {
            return false;
        }
        if ($hour_int   > 23) {
            return false;
        }
        if ($minute_int > 59) {
            return false;
        }

        return true;
    }
}
