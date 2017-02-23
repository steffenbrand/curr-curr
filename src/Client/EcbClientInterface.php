<?php

namespace SteffenBrand\CurrCurr\Client;

use Psr\SimpleCache\CacheInterface;
use SteffenBrand\CurrCurr\Mapper\MapperInterface;
use SteffenBrand\CurrCurr\Model\CacheConfig;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

interface EcbClientInterface
{

    /**
     * @const string
     */
    const DEFAULT_EXCHANGE_RATES_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    /**
     * @param string $exchangeRatesUrl
     * @param CacheConfig $cacheConfig
     * @param MapperInterface $mapper
     */
    public function __construct(string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL,
                                CacheConfig $cacheConfig = null,
                                MapperInterface $mapper = null);

    /**
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array;

}