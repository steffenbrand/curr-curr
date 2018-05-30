<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Exception;

/**
 * Class CurrencyNotSupportedException
 * @package SteffenBrand\CurrCurr\Exception
 */
class CurrencyNotSupportedException extends \UnexpectedValueException
{
    /**
     * CurrencyNotSupportedException constructor.
     */
    public function __construct()
    {
        parent::__construct('The currency you are requesting the exchange rates for is not supported.');
    }
}