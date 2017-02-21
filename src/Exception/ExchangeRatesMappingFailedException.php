<?php

namespace SteffenBrand\CurrCurr\Exception;

class ExchangeRatesMappingFailedException extends \RuntimeException
{

    public function __construct()
    {
        parent::__construct('Could not successfully parse and map exchange rates.');
    }

}