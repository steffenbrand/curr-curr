<?php

namespace SteffenBrand\CurrCurr\Client;

use Psr\SimpleCache\CacheInterface;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

interface EcbClientInterface
{

    /**
     * @param string $exchangeRatesUrl
     * @param CacheInterface $cache
     * @param int $cacheTimeInSeconds
     */
    public function __construct(string $exchangeRatesUrl, CacheInterface $cache, int $cacheTimeInSeconds);

    /**
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array;

}