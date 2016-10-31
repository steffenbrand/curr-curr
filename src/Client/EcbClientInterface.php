<?php

namespace SteffenBrand\CurrCurr\Client;

use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

interface EcbClientInterface
{

    /**
     * @param string $exchangeRatesUrl
     */
    public function __construct(string $exchangeRatesUrl);

    /**
     * @throws ExchangeRatesRequestFailedException
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array;

}