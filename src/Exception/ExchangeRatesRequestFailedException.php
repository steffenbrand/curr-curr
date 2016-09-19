<?php

namespace SteffenBrand\CurrCurr\Exception;

use Exception;
use RuntimeException;

class ExchangeRatesRequestFailedException extends RuntimeException
{

    public function __construct(Exception $e)
    {
        parent::__construct('Request for ECBs exchange rates failed.', $e->getCode(), $e);
    }

}