<?php

declare(strict_types=1);

namespace SteffenBrand\CurrCurr\Test;

use PHPUnit\Framework\TestCase;
use SteffenBrand\CurrCurr\CurrCurr;
use SteffenBrand\CurrCurr\Model\Currency;
use SteffenBrand\CurrCurr\Model\ExchangeRate;
use SteffenBrand\CurrCurr\Test\Client\EcbClientMock;

/**
 * @runTestsInSeparateProcesses
 */
class CurrCurrMockTest extends TestCase
{
    public function testIsInstantiable(): void
    {
        $this->assertInstanceOf(CurrCurr::class, $this->getInstance(EcbClientMock::VALID_RESPONSE));
    }

    public function testGetExchangeRates(): void
    {
        $cc = $this->getInstance(EcbClientMock::VALID_RESPONSE);
        $exchangeRates = $cc->getExchangeRates();

        $this->assertNotNull($exchangeRates, 'exchange rates must not be null');
        $this->assertNotEmpty($exchangeRates, 'exchange rates must not be empty');
        $this->assertArrayHasKey(Currency::USD, $exchangeRates, 'exchange rates must contain USD');
        $this->assertInstanceOf(\DateTime::class, $exchangeRates[Currency::USD]->getDate(), 'date must be instance of DateTime');
        $this->assertEquals(new \DateTime('2016-10-17 00:00:00'), $exchangeRates[Currency::USD]->getDate(), 'Date must be 2016-10-17 00:00:00');
        $this->assertEquals(Currency::USD, $exchangeRates[Currency::USD]->getCurrency(), 'Currency must be ' . Currency::USD);
        $this->assertEquals(1.0994, $exchangeRates[Currency::USD]->getRate(), 'Rate must be 1.0994');
    }

    public function testGetExchangeRateByCurrency(): void
    {
        $cc = $this->getInstance(EcbClientMock::VALID_RESPONSE);
        $exchangeRate = $cc->getExchangeRateByCurrency(Currency::USD);

        $this->assertNotNull($exchangeRate, 'exchange rate must not be null');
        $this->assertInstanceOf(ExchangeRate::class, $exchangeRate, 'exchange rate must be instance of ExchangeRate');
        $this->assertInstanceOf(\DateTime::class, $exchangeRate->getDate(), 'date must be instance of DateTime');
        $this->assertNotEmpty($exchangeRate->getRate(), 'rate must not be empty');
        $this->assertNotEmpty($exchangeRate->getCurrency(), 'currency must not be empty');
        $this->assertEquals(new \DateTime('2016-10-17 00:00:00'), $exchangeRate->getDate(), 'Date must be 2016-10-17 00:00:00');
        $this->assertEquals(Currency::USD, $exchangeRate->getCurrency(), 'Currency must be ' . Currency::USD);
        $this->assertEquals(1.0994, $exchangeRate->getRate(), 'Rate must be 1.0994');
    }

    public function testSomeStringThrowsCurrencyNotSupportedException(): void
    {
        $this->expectException(\SteffenBrand\CurrCurr\Exception\CurrencyNotSupportedException::class);
        $this->expectExceptionMessage('The currency you are requesting the exchange rates for is not supported.');

        $cc = $this->getInstance(EcbClientMock::VALID_RESPONSE);
        $cc->getExchangeRateByCurrency('SOMESTRING');
    }

    public function testMissingUsdThrowsCurrencyNotSupportedException(): void
    {
        $this->expectException(\SteffenBrand\CurrCurr\Exception\CurrencyNotSupportedException::class);
        $this->expectExceptionMessage('The currency you are requesting the exchange rates for is not supported.');
        $cc = $this->getInstance(EcbClientMock::USD_MISSING_RESPONSE);
        $cc->getExchangeRateByCurrency(Currency::USD);
    }

    /**
     */
    public function testMissingDateThrowsExchangeRatesMappingFailedException(): void
    {
        $this->expectException(\SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException::class);
        $this->expectExceptionMessage('Could not successfully parse and map exchange rates.');
        $cc = $this->getInstance(EcbClientMock::DATE_MISSING_RESPONSE);
        $cc->getExchangeRates();
    }

    /**
     * @param string $expectedResponse
     * @return CurrCurr
     */
    private function getInstance(string $expectedResponse): CurrCurr
    {
        return new CurrCurr(new EcbClientMock($expectedResponse));
    }
}
