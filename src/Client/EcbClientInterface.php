<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Client;

use SteffenBrand\CurrCurr\Mapper\MapperInterface;
use SteffenBrand\CurrCurr\Model\CacheConfig;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

interface EcbClientInterface
{
    /**
     * @const string
     */
    public const DEFAULT_EXCHANGE_RATES_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    /**
     * EcbClientInterface constructor.
     *
     * @param string $exchangeRatesUrl
     * @param CacheConfig|null $cacheConfig
     * @param MapperInterface|null $mapper
     */
    public function __construct(
        string $exchangeRatesUrl = self::DEFAULT_EXCHANGE_RATES_URL,
        CacheConfig $cacheConfig = null,
        MapperInterface $mapper = null
    );

    /**
     * Get exchange rates.
     *
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array;
}