<?php

namespace SteffenBrand\CurrCurr\Exception;

use RuntimeException;

class ExchangeRatesMappingFailedException extends RuntimeException
{

    public function __construct()
    {
        parent::__construct('Could not successfully parse and map exchange rates.');
    }

}