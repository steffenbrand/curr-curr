<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Exception;

/**
 * Class ExchangeRatesMappingFailedException
 * @package SteffenBrand\CurrCurr\Exception
 */
class ExchangeRatesMappingFailedException extends \RuntimeException
{
    /**
     * ExchangeRatesMappingFailedException constructor.
     */
    public function __construct()
    {
        parent::__construct('Could not successfully parse and map exchange rates.');
    }
}