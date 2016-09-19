<?php

namespace SteffenBrand\CurrCurr\Test;

use DateTime;
use PHPUnit_Framework_TestCase;
use SteffenBrand\CurrCurr\EcbClient;
use SteffenBrand\CurrCurr\CurrCurr;
use SteffenBrand\CurrCurr\Model\Currency;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class CurrCurrIntegrationTest extends PHPUnit_Framework_TestCase
{

    public function testIsInstantiable()
    {
        $this->assertInstanceOf(CurrCurr::class, $this->getInstance());
    }

    public function testGetExchangeRates()
    {
        $cc = $this->getInstance();
        $exchangeRates = $cc->getExchangeRates();

        $this->assertNotNull($exchangeRates, "exchange rates must not be null");
        $this->assertNotEmpty($exchangeRates, "exchange rates must not be empty");
        $this->assertArrayHasKey(Currency::USD, $exchangeRates, "exchange rates must contain USD");
        $this->assertInstanceOf(DateTime::class, $exchangeRates[Currency::USD]->getDate(), "date must be instance of DateTime");
    }

    public function testGetExchangeRateByCurrency()
    {
        $cc = $this->getInstance();
        $exchangeRate = $cc->getExchangeRateByCurrency(Currency::USD);

        $this->assertNotNull($exchangeRate, "exchange rates must not be null");
        $this->assertInstanceOf(ExchangeRate::class, $exchangeRate, "exchange rate must be instance of ExchangeRate");
        $this->assertInstanceOf(DateTime::class, $exchangeRate->getDate(), "date must be instance of DateTime");
        $this->assertNotEmpty($exchangeRate->getRate(), "rate must not be empty");
        $this->assertNotEmpty($exchangeRate->getCurrency(), "currency must not be empty");
    }

    /**
     * @expectedException SteffenBrand\CurrCurr\Exception\CurrencyNotSupportedException
     * @expectedExceptionMessage The currency you are requesting the exchange rates for is not supported.
     */
    public function testGetExchangeRateByCurrencyThrowsCurrencyNotSupportedException()
    {
        $cc = $this->getInstance();
        $cc->getExchangeRateByCurrency("SOMESTRING");
    }

    /**
     * @expectedException SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException
     * @expectedExceptionMessage Request for ECBs exchange rates failed.
     */
    public function testGetExchangeRatesThrowsExchangeRatesRequestFailedException()
    {
        $cc = $this->getInstance("http://www.sb-webworks.de/not-found.xml");
        $cc->getExchangeRates();
    }

    /**
     * @expectedException SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException
     * @expectedExceptionMessage Could not successfully parse and map exchange rates.
     */
    public function testGetExchangeRatesThrowsExchangeRatesMappingFailedException()
    {
        $cc = $this->getInstance("https://www.google.de");
        $cc->getExchangeRates();
    }

    /**
     * @param string $exchangeRatesUrl
     * @return CurrCurr
     */
    private function getInstance(string $exchangeRatesUrl = EcbClient::DEFAULT_EXCHANGE_RATES_URL): CurrCurr
    {
        return new CurrCurr($exchangeRatesUrl);
    }

}