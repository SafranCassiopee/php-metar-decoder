<?php

namespace MetarDecoder\Exception;

class DatasetLoadingException extends \Exception
{
    
    public function __construct($message)
    {
        parent::__construct($message);
    }

}
