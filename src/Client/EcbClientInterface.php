<?php

namespace SteffenBrand\CurrCurr\Client;

use Psr\SimpleCache\CacheInterface;
use SteffenBrand\CurrCurr\Mapper\MapperInterface;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

interface EcbClientInterface
{

    /**
     * @const string
     */
    const DEFAULT_EXCHANGE_RATES_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    /**
     * @const int
     */
    const CACHE_UNTIL_MIDNIGHT = -1;

    /**
     * @const string
     */
    const DEFAULT_CACHE_KEY = 'curr-curr-cache';

    /**
     * @param string $exchangeRatesUrl
     * @param CacheInterface $cache
     * @param int $cacheTimeInSeconds
     * @param string $cacheKey
     * @param MapperInterface $mapper
     */
    public function __construct(string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL,
                                CacheInterface $cache = null,
                                int $cacheTimeInSeconds = self::CACHE_UNTIL_MIDNIGHT,
                                string $cacheKey = self::DEFAULT_CACHE_KEY,
                                MapperInterface $mapper = null);

    /**
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array;

}