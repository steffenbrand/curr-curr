<?php

namespace SteffenBrand\CurrCurr;

use SteffenBrand\CurrCurr\Client\EcbClient;
use SteffenBrand\CurrCurr\Client\EcbClientInterface;
use SteffenBrand\CurrCurr\Exception\CurrencyNotSupportedException;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesMappingFailedException;
use SteffenBrand\CurrCurr\Exception\ExchangeRatesRequestFailedException;
use SteffenBrand\CurrCurr\Model\Currency;
use SteffenBrand\CurrCurr\Model\ExchangeRate;

class CurrCurr
{

    /**
     * @var EcbClient
     */
    private $ecbClient;

    /**
     * CurrCurr constructor.
     * @param EcbClientInterface $ecbClient The ECB Client to use, leave blank for default ECB Client
     */
    public function __construct(EcbClientInterface $ecbClient = null)
    {
        if (null === $ecbClient) {
            $ecbClient = new EcbClient();
        }
        $this->ecbClient = $ecbClient;
    }

    /**
     * Delivers the current exchange rates based on EUR from the ECB
     *
     * @throws ExchangeRatesMappingFailedException|ExchangeRatesRequestFailedException
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        return $this->ecbClient->getExchangeRates();
    }

    /**
     * Delivers the current exchange rates for a specific currency based on EUR from the ECB
     *
     * @throws ExchangeRatesMappingFailedException|ExchangeRatesRequestFailedException|CurrencyNotSupportedException
     * @param string $currencyAbbr The currency to be requested. Use constants from Currency.
     * @return ExchangeRate
     */
    public function getExchangeRateByCurrency(string $currencyAbbr): ExchangeRate
    {
        $exchangeRates = $this->getExchangeRates();

        if (in_array($currencyAbbr, Currency::ALLOWED_CURRENCIES) === false
        ||  array_key_exists($currencyAbbr, $exchangeRates) === false) {
            throw new CurrencyNotSupportedException();
        }

        return $exchangeRates[$currencyAbbr];
    }

}