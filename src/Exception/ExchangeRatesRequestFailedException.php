<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Exception;

/**
 * Class ExchangeRatesRequestFailedException
 * @package SteffenBrand\CurrCurr\Exception
 */
class ExchangeRatesRequestFailedException extends \RuntimeException
{
    /**
     * ExchangeRatesRequestFailedException constructor.
     * @param \Exception $e
     */
    public function __construct(\Exception $e)
    {
        parent::__construct('Request for ECBs exchange rates failed.', $e->getCode(), $e);
    }
}