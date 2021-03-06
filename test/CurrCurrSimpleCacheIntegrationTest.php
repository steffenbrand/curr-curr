<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Test;

use Odan\Cache\Simple\OpCache;
use PHPUnit\Framework\TestCase;
use SteffenBrand\CurrCurr\Client\EcbClient;
use SteffenBrand\CurrCurr\CurrCurr;
use SteffenBrand\CurrCurr\Model\CacheConfig;
use SteffenBrand\CurrCurr\Model\Currency;

/**
 * @runTestsInSeparateProcesses
 */
class CurrCurrSimpleCacheIntegrationTest extends TestCase
{
    public function testIsInstantiable(): void
    {
        $this->assertInstanceOf(CurrCurr::class, $this->getInstance(uniqid('test', false)));
    }

    public function testGetExchangeRatesMidnightCache(): void
    {
        $cc = $this->getInstance(uniqid('test', false));
        $this->getRates($cc);
        $this->getRates($cc);
    }

    /**
     * @param string $cacheKey
     * @return CurrCurr
     */
    private function getInstance(string $cacheKey): CurrCurr
    {
        return new CurrCurr(
            new EcbClient(
                EcbClient::DEFAULT_EXCHANGE_RATES_URL,
                new CacheConfig(
                    new OpCache(sys_get_temp_dir() . '/cache'),
                    CacheConfig::CACHE_UNTIL_MIDNIGHT,
                    $cacheKey
                )
            )
        );
    }

    /**
     * @param CurrCurr $cc
     */
    private function getRates(CurrCurr $cc): void
    {
        $exchangeRates = $cc->getExchangeRates();

        $this->assertNotNull($exchangeRates, 'exchange rates must not be null');
        $this->assertNotEmpty($exchangeRates, 'exchange rates must not be empty');
        $this->assertArrayHasKey(Currency::USD, $exchangeRates, 'exchange rates must contain USD');
        $this->assertInstanceOf(\DateTime::class, $exchangeRates[Currency::USD]->getDate(), 'date must be instance of DateTime');
    }
}