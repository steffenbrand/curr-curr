<?php

namespace SteffenBrand\CurrCurr;

use Psr\SimpleCache\CacheInterface;
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
     * @var EcbClientInterface
     */
    private $client;

    /**
     * CurrCurr constructor.
     * @param EcbClientInterface $client The ECB Client to use, leave blank for default ECB Client
     */
    public function __construct(EcbClientInterface $client = null)
    {
        if (null === $client) {
            $client = new EcbClient();
        }
        $this->client = $client;
    }

    /**
     * Delivers the current exchange rates based on EUR from the ECB
     *
     * @throws ExchangeRatesMappingFailedException|ExchangeRatesRequestFailedException
     * @return ExchangeRate[]
     */
    public function getExchangeRates(): array
    {
        return $this->client->getExchangeRates();
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