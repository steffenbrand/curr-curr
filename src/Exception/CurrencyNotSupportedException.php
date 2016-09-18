<?php

namespace SteffenBrand\CurrCurr\Exception;

use UnexpectedValueException;

class CurrencyNotSupportedException extends UnexpectedValueException
{

    public function __construct()
    {
        parent::__construct('The currency you are requesting the exchange rates for is not supported.');
    }

}