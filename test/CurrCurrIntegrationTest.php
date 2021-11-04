<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Test;

use PHPUnit\Framework\TestCase;
use SteffenBrand\CurrCurr\Client\EcbClient;
use SteffenBrand\CurrCurr\CurrCurr;
use SteffenBrand\CurrCurr\Model\Currency;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

/**
 * @runTestsInSeparateProcesses
 */
class CurrCurrIntegrationTest extends TestCase
{
    public function testIsInstantiable(): void
    {
        $this->assertInstanceOf(CurrCurr::class, $this->getInstance());
    }

    public function testGetExchangeRates(): void
    {
        $cc = $this->getInstance();
        $exchangeRates = $cc->getExchangeRates();

        $this->assertNotNull($exchangeRates, 'exchange rates must not be null');
        $this->assertNotEmpty($exchangeRates, 'exchange rates must not be empty');
        $this->assertArrayHasKey(Currency::USD, $exchangeRates, 'exchange rates must contain USD');
        $this->assertInstanceOf(\DateTime::class, $exchangeRates[Currency::USD]->getDate(), 'date must be instance of DateTime');
    }

    public function testGetExchangeRateByCurrency(): void
    {
        $cc = $this->getInstance();
        $exchangeRate = $cc->getExchangeRateByCurrency(Currency::USD);

        $this->assertNotNull($exchangeRate, 'exchange rates must not be null');
        $this->assertInstanceOf(ExchangeRate::class, $exchangeRate, 'exchange rate must be instance of ExchangeRate');
        $this->assertInstanceOf(\DateTime::class, $exchangeRate->getDate(), 'date must be instance of DateTime');
        $this->assertNotEmpty($exchangeRate->getRate(), 'rate must not be empty');
        $this->assertNotEmpty($exchangeRate->getCurrency(), 'currency must not be empty');
    }

    public function testGetExchangeRateByCurrencyThrowsCurrencyNotSupportedException(): void
    {
        $this->expectException(\SteffenBrand\CurrCurr\Exception\CurrencyNotSupportedException::class);
        $this->expectExceptionMessage('The currency you are requesting the exchange rates for is not supported.');

        $cc = $this->getInstance();
        $cc->getExchangeRateByCurrency('SOMESTRING');
    }

    public function testGetExchangeRatesThrowsExchangeRatesRequestFailedException(): void
    {
        $this->expectException(\SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException::class);
        $this->expectExceptionMessage('Request for ECBs exchange rates failed.');

        $cc = $this->getInstance('http://httpstat.us/404');
        $cc->getExchangeRates();
    }

    public function testGetExchangeRatesThrowsExchangeRatesMappingFailedException(): void
    {
        $this->expectException(\SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException::class);
        $this->expectExceptionMessage('Could not successfully parse and map exchange rates.');

        $cc = $this->getInstance('http://httpstat.us/200');
        $cc->getExchangeRates();
    }

    /**
     * @param string $exchangeRatesUrl
     * @return CurrCurr
     */
    private function getInstance(string $exchangeRatesUrl = null): CurrCurr
    {
        if (null === $exchangeRatesUrl) {
            return new CurrCurr();
        }
        return new CurrCurr(new EcbClient($exchangeRatesUrl));
    }
}
